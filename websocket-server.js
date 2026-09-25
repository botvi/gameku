import { WebSocketServer, WebSocket } from 'ws';
import http from 'http';

const server = http.createServer();
const wss = new WebSocketServer({ server });

// Store room connections: room_id => { players: Map(userId => ws), readyStates: Map(userId => readyBool), customizations: Map(userId => customObj), names: Map(userId => nameStr) }
const rooms = new Map();

// Global chat history — simpan 50 pesan terakhir, bertahan selama server running
const CHAT_HISTORY_MAX = 50;
const chatHistory = [];

wss.on('connection', (ws) => {
    let currentRoomId = null;
    let userId = null;

    ws.on('message', (message) => {
        try {
            const data = JSON.parse(message);
            const { type, roomId, payload } = data;

            if (type === 'join') {
                currentRoomId = roomId;
                userId = payload.userId;
                ws.userId = userId;
                ws.userName = payload.userName;
                ws.isSpectator = payload.isSpectator || false;

                if (!rooms.has(roomId)) {
                    rooms.set(roomId, {
                        players: new Map(),
                        readyStates: new Map(),
                        arenaReadyStates: new Map(),
                        customizations: new Map(),
                        names: new Map(),
                        raceStates: new Map(),
                        gameStarted: false,
                        botId: null,
                        botName: null,
                        botCustomizations: null,
                        botReadyTimer: null,
                        botRaceTimer: null,
                        cleanupTimer: null
                    });
                }

                const room = rooms.get(roomId);
                room.players.set(userId, ws);
                room.customizations.set(userId, payload.customizations);
                room.names.set(userId, payload.userName);

                // Cancel any pending cleanup (player reconnected)
                if (room.cleanupTimer) {
                    clearTimeout(room.cleanupTimer);
                    room.cleanupTimer = null;
                }

                console.log(`User ${payload.userName} (ID: ${userId}, Spectator: ${ws.isSpectator}) joined room ${roomId}`);

                // Register bot opponent if this room has one (only once)
                if (payload.botId && !room.players.has(payload.botId)) {
                    registerBot(roomId, payload.botId, payload.botName, payload.botCustomizations);
                }

                // Kirim riwayat chat ke user baru yang join global_chat
                if (roomId === 'global_chat' && chatHistory.length > 0) {
                    if (ws.readyState === WebSocket.OPEN) {
                        ws.send(JSON.stringify({
                            type: 'chat_history',
                            payload: chatHistory
                        }));
                    }
                }

                // Notify all players in room about the current players list
                broadcastToRoom(roomId, {
                    type: 'room_update',
                    payload: getRoomPlayersData(roomId)
                });

                // Send current arena ready states
                if (room.arenaReadyStates.size > 0 && ws.readyState === WebSocket.OPEN) {
                    ws.send(JSON.stringify({
                        type: 'arena_ready_update',
                        payload: {
                            readyStates: Object.fromEntries(room.arenaReadyStates)
                        }
                    }));
                }

                // If game is already in progress, send current race states to the joining player
                if (room.gameStarted && room.raceStates.size > 0) {
                    const raceData = {};
                    for (const [pId, pState] of room.raceStates.entries()) {
                        raceData[pId] = pState;
                    }
                    if (ws.readyState === WebSocket.OPEN) {
                        ws.send(JSON.stringify({
                            type: 'game_in_progress',
                            payload: {
                                raceStates: raceData
                            }
                        }));
                    }
                }
            }

            else if (type === 'ready') {
                const room = rooms.get(currentRoomId);
                if (room) {
                    room.readyStates.set(userId, payload.ready);
                    console.log(`User (ID: ${userId}) ready status in room ${currentRoomId} set to: ${payload.ready}`);

                    broadcastToRoom(currentRoomId, {
                        type: 'room_update',
                        payload: getRoomPlayersData(currentRoomId)
                    });

                    // If the human player is ready and there's a bot, make the bot ready after a short delay
                    if (payload.ready && room.botId && !room.readyStates.get(room.botId)) {
                        scheduleBotReady(roomId);
                    }

                    // Check if both players are ready
                    const playersArray = Array.from(room.players.keys());
                    if (playersArray.length === 2 &&
                        room.readyStates.get(playersArray[0]) === true &&
                        room.readyStates.get(playersArray[1]) === true) {

                        console.log(`Both players ready in room ${currentRoomId}. Starting game...`);
                        broadcastToRoom(currentRoomId, {
                            type: 'game_start',
                            payload: {
                                roomId: currentRoomId
                            }
                        });
                    }
                }
            }

            else if (type === 'arena_ready') {
                const room = rooms.get(currentRoomId);
                if (room) {
                    room.arenaReadyStates.set(userId, true);
                    console.log(`User (ID: ${userId}) arena ready status set to: true`);

                    // Broadcast arena_ready_update to everyone (including spectators)
                    broadcastToRoom(currentRoomId, {
                        type: 'arena_ready_update',
                        payload: {
                            userId: userId,
                            readyStates: Object.fromEntries(room.arenaReadyStates)
                        }
                    });

                    // Check non-spectator active players
                    const activePlayerIds = Array.from(room.players.entries())
                        .filter(([pId, pWs]) => !pWs || !pWs.isSpectator)
                        .map(([pId]) => pId);

                    // If there's a bot, make it arena-ready too (after a short delay)
                    if (room.botId && !room.arenaReadyStates.get(room.botId)) {
                        setTimeout(() => {
                            const r = rooms.get(currentRoomId);
                            if (!r) return;
                            r.arenaReadyStates.set(r.botId, true);
                            console.log(`Bot (ID: ${r.botId}) arena ready status set to: true`);

                            const botActivePlayers = Array.from(r.players.entries())
                                .filter(([pId, pWs]) => !pWs || !pWs.isSpectator)
                                .map(([pId]) => pId);

                            if (botActivePlayers.length >= 2 &&
                                r.arenaReadyStates.get(botActivePlayers[0]) === true &&
                                r.arenaReadyStates.get(botActivePlayers[1]) === true) {

                                r.gameStarted = true;
                                console.log(`Both active players arena-ready in room ${currentRoomId}. Broadcasting countdown start...`);
                                broadcastToRoom(currentRoomId, {
                                    type: 'start_countdown',
                                    payload: {}
                                });

                                // Start bot race simulation
                                startBotRace(currentRoomId);
                            }
                        }, 800);
                    } else {
                        if (activePlayerIds.length >= 2 &&
                            room.arenaReadyStates.get(activePlayerIds[0]) === true &&
                            room.arenaReadyStates.get(activePlayerIds[1]) === true) {

                            room.gameStarted = true;
                            console.log(`Both active players arena-ready in room ${currentRoomId}. Broadcasting countdown start...`);
                            broadcastToRoom(currentRoomId, {
                                type: 'start_countdown',
                                payload: {}
                            });
                        }
                    }
                }
            }

            else if (type === 'game_state_sync') {
                // Relay game state to the opponent (speed, distance) and save in room
                const room = rooms.get(currentRoomId);
                if (room) {
                    room.raceStates.set(userId, {
                        speed: payload.speed,
                        distance: payload.distance,
                        timestamp: Date.now()
                    });

                    for (const [pId, pWs] of room.players.entries()) {
                        if (pId !== userId && pWs.readyState === WebSocket.OPEN) {
                            pWs.send(JSON.stringify({
                                type: 'opponent_sync',
                                payload: {
                                    userId: userId,
                                    speed: payload.speed,
                                    distance: payload.distance,
                                    isTapped: payload.isTapped,
                                    feedback: payload.feedback || null,
                                    feedbackStroke: payload.feedbackStroke || null,
                                    tintTop: payload.tintTop || null,
                                    tintBottom: payload.tintBottom || null
                                }
                            }));
                        }
                    }
                }
            }

            else if (type === 'game_over') {
                const room = rooms.get(currentRoomId);
                if (room) {
                    room.gameStarted = false;
                    room.raceStates.clear();
                    stopBotRace(currentRoomId);
                    console.log(`Game over in room ${currentRoomId}. Winner ID: ${payload.winnerId}`);
                    broadcastToRoom(currentRoomId, {
                        type: 'game_finished',
                        payload: {
                            winnerId: payload.winnerId
                        }
                    });
                }
            }

            else if (type === 'global_chat') {
                // Broadcast chat ke semua user yang join global_chat room
                const msg = (payload.message || '').toString().trim().slice(0, 200);
                if (!msg) return;
                const chatMsg = {
                    userId: userId,
                    userName: payload.userName || 'Anonim',
                    message: msg,
                    timestamp: Date.now()
                };
                // Simpan ke history
                chatHistory.push(chatMsg);
                if (chatHistory.length > CHAT_HISTORY_MAX) chatHistory.shift();

                broadcastToRoom('global_chat', {
                    type: 'global_chat',
                    payload: chatMsg
                });
                console.log(`[GLOBAL CHAT] ${chatMsg.userName}: ${msg}`);
            }
        } catch (e) {
            console.error('Error handling message:', e);
        }
    });

    ws.on('close', () => {
        if (currentRoomId && rooms.has(currentRoomId)) {
            const room = rooms.get(currentRoomId);
            room.players.delete(userId);
            room.readyStates.delete(userId);
            room.arenaReadyStates.delete(userId);
            room.customizations.delete(userId);
            room.names.delete(userId);

            console.log(`User (ID: ${userId}) disconnected from room ${currentRoomId}`);

            if (room.players.size === 0) {
                // Jangan hapus global_chat agar history tetap bertahan
                if (currentRoomId !== 'global_chat') {
                    stopBotRace(currentRoomId);
                    rooms.delete(currentRoomId);
                    console.log(`Room ${currentRoomId} is empty. Deleting room.`);
                }
            } else {
                broadcastToRoom(currentRoomId, {
                    type: 'room_update',
                    payload: getRoomPlayersData(currentRoomId)
                });
            }
        }
    });
});

function broadcastToRoom(roomId, messageObj) {
    const room = rooms.get(roomId);
    if (room) {
        const msgStr = JSON.stringify(messageObj);
        for (const ws of room.players.values()) {
            if (ws.readyState === WebSocket.OPEN) {
                ws.send(msgStr);
            }
        }
    }
}

function getRoomPlayersData(roomId) {
    const room = rooms.get(roomId);
    if (!room) return { players: [] };
    const playersData = [];
    for (const [pId, pWs] of room.players.entries()) {
        playersData.push({
            userId: pId,
            userName: room.names.get(pId),
            ready: room.readyStates.get(pId) || false,
            customizations: room.customizations.get(pId)
        });
    }
    return {
        players: playersData
    };
}

// ============================================================
//  BOT AI SIMULATION
// ============================================================

// Register a bot as a virtual player in the room (no real WebSocket connection)
function registerBot(roomId, botId, botName, botCustomizations) {
    const room = rooms.get(roomId);
    if (!room) return;

    room.botId = botId;
    room.botName = botName;
    room.botCustomizations = botCustomizations || {};

    // Add bot as a virtual player (ws = null means it's a bot)
    room.players.set(botId, null);
    room.names.set(botId, botName);
    room.customizations.set(botId, botCustomizations || {});
    room.readyStates.set(botId, false);
    room.arenaReadyStates.set(botId, false);

    console.log(`Bot ${botName} (ID: ${botId}) registered in room ${roomId}`);

    // Notify all players about the bot joining
    broadcastToRoom(roomId, {
        type: 'room_update',
        payload: getRoomPlayersData(roomId)
    });
}

// Make the bot ready after a short delay (simulates human thinking time)
function scheduleBotReady(roomId) {
    const room = rooms.get(roomId);
    if (!room || !room.botId) return;

    // Clear any existing timer
    if (room.botReadyTimer) {
        clearTimeout(room.botReadyTimer);
    }

    const delay = 600 + Math.random() * 1200; // 0.6s - 1.8s
    room.botReadyTimer = setTimeout(() => {
        const r = rooms.get(roomId);
        if (!r || !r.botId) return;
        if (r.readyStates.get(r.botId) === true) return;

        r.readyStates.set(r.botId, true);
        console.log(`Bot (ID: ${r.botId}) ready status in room ${roomId} set to: true`);

        broadcastToRoom(roomId, {
            type: 'room_update',
            payload: getRoomPlayersData(roomId)
        });

        // Check if both players are ready
        const playersArray = Array.from(r.players.keys());
        if (playersArray.length === 2 &&
            r.readyStates.get(playersArray[0]) === true &&
            r.readyStates.get(playersArray[1]) === true) {

            console.log(`Both players ready in room ${roomId}. Starting game...`);
            broadcastToRoom(roomId, {
                type: 'game_start',
                payload: {
                    roomId: roomId
                }
            });
        }
    }, delay);
}

// Start the bot's race simulation (sends game_state_sync periodically)
function startBotRace(roomId) {
    const room = rooms.get(roomId);
    if (!room || !room.botId) return;

    // Stop any existing race timer
    if (room.botRaceTimer) {
        clearInterval(room.botRaceTimer);
    }

    const RACE_DISTANCE = 1000;
    const botId = room.botId;
    let botDistance = RACE_DISTANCE;
    // Bot speed: random between 6.5 and 9.5 (slightly above/below player base speed of 5.0)
    const botSpeed = 6.5 + Math.random() * 3.0;
    let lastTime = Date.now();

    room.raceStates.set(botId, {
        speed: botSpeed,
        distance: botDistance,
        timestamp: Date.now()
    });

    room.botRaceTimer = setInterval(() => {
        const r = rooms.get(roomId);
        if (!r || !r.botId) return;

        const now = Date.now();
        const delta = now - lastTime;
        lastTime = now;

        // Decrease distance based on speed (same formula as the client)
        botDistance = Math.max(0, botDistance - botSpeed * (delta / 1000));

        r.raceStates.set(botId, {
            speed: botSpeed,
            distance: botDistance,
            timestamp: now
        });

        // Send opponent_sync to the human player
        for (const [pId, pWs] of r.players.entries()) {
            if (pId !== botId && pWs && pWs.readyState === WebSocket.OPEN) {
                pWs.send(JSON.stringify({
                    type: 'opponent_sync',
                    payload: {
                        userId: botId,
                        speed: botSpeed,
                        distance: botDistance,
                        isTapped: false
                    }
                }));
            }
        }

        // Bot finished the race
        if (botDistance <= 0) {
            console.log(`Bot (ID: ${botId}) finished the race in room ${roomId}`);
            stopBotRace(roomId);
            r.gameStarted = false;
            r.raceStates.clear();
            broadcastToRoom(roomId, {
                type: 'game_finished',
                payload: {
                    winnerId: botId
                }
            });
        }
    }, 100);
}

// Stop the bot's race simulation
function stopBotRace(roomId) {
    const room = rooms.get(roomId);
    if (!room) return;
    if (room.botRaceTimer) {
        clearInterval(room.botRaceTimer);
        room.botRaceTimer = null;
    }
    if (room.botReadyTimer) {
        clearTimeout(room.botReadyTimer);
        room.botReadyTimer = null;
    }
}

const PORT = 8080;
server.listen(PORT, () => {
    console.log(`WebSocket server listening on port ${PORT}`);
});
