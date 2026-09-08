@extends('layouts.game')

@section('title', 'Pacu Jalur: The Pixel ” Custom Room')

@section('content')
<style>
    body {
        margin: 0;
        padding: 0;
        background-color: #060d18;
        color: #e2e8f0;
        font-family: 'Press Start 2P', monospace;
        overflow: hidden;
    }

    #game-ui {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        background: url('{{ asset_v('game_pacu/assets/image/bg/bgmenu.jpg') }}') no-repeat center center;
        background-size: cover;
        z-index: 10;
        padding-bottom: 0;
        box-sizing: border-box;
        overflow: hidden;
    }

    #game-ui .game-ui-scroll {
        flex: 1 1 0;
        min-height: 0;
        width: 100%;
        overflow-y: auto;
        overflow-x: hidden;
        -webkit-overflow-scrolling: touch;
        touch-action: pan-y;
        overscroll-behavior: contain;
        display: flex;
        flex-direction: column;
        align-items: center;
        box-sizing: border-box;
        padding-bottom: 24px;
        z-index: 11;
    }

    /* --- Dynamic Backdrop Glow --- */
    .ps5-backdrop-glow {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1;
        pointer-events: none;
        transition: background 0.8s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    .bg-slide-4 {
        background: rgba(15, 5, 20, 0.9);
    }

    .back-btn {
        position: absolute;
        top: 16px;
        left: 14px;
        width: 36px;
        height: 36px;
        background: none;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 15;
        transition: all 0.15s ease;
        box-sizing: border-box;
    }

    .back-btn img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        image-rendering: pixelated;
    }

    .back-btn:hover {
        transform: scale(1.05);
    }

    .back-btn:active {
        transform: scale(0.9);
    }

    .title-banner {
        font-family: 'Press Start 2P', monospace;
        font-size: 12px;
        background: linear-gradient(180deg, #ffffff 0%, #a5f3fc 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        filter: drop-shadow(0px 2px 4px rgba(0, 0, 0, 0.8));
        margin-top: 76px;
        margin-bottom: 15px;
        text-align: center;
        line-height: 1.4;
        letter-spacing: 2px;
        z-index: 11;
    }

    .panel {
        background: rgba(10, 18, 36, 0.7);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        width: 88%;
        padding: 22px 20px;
        margin-bottom: 20px;
        box-shadow:
            0 12px 40px rgba(0, 0, 0, 0.6),
            0 0 0 1px rgba(255, 255, 255, 0.04),
            inset 0 1px 0 rgba(255, 255, 255, 0.06);
        box-sizing: border-box;
        display: flex;
        flex-direction: column;
        z-index: 11;
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        position: relative;
        overflow: hidden;
    }

    .panel::before {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: repeating-linear-gradient(
            0deg, transparent, transparent 3px,
            rgba(0,0,0,0.025) 3px, rgba(0,0,0,0.025) 4px
        );
        pointer-events: none;
        z-index: 0;
        border-radius: 20px;
    }

    .panel-title {
        font-family: 'Press Start 2P', monospace;
        font-size: 8px;
        color: #ffaa00;
        margin-bottom: 15px;
        border-bottom: 2px dashed rgba(255, 255, 255, 0.15);
        padding-bottom: 12px;
        text-align: center;
        line-height: 1.5;
        text-shadow: 0 2px 4px rgba(0,0,0,0.6);
    }

    /* Daftar Room Scrollable */
    .room-list-container {
        flex: 1;
        min-height: 0;
        max-height: 230px;
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
        touch-action: pan-y;
        overscroll-behavior: contain;
        padding-right: 6px;
        scrollbar-width: thin;
        scrollbar-color: #a855f7 rgba(15, 23, 42, 0.3);
    }

    .room-list-container::-webkit-scrollbar {
        width: 6px;
    }

    .room-list-container::-webkit-scrollbar-track {
        background: rgba(15, 23, 42, 0.3);
        border-radius: 4px;
    }

    .room-list-container::-webkit-scrollbar-thumb {
        background-color: #a855f7;
        border-radius: 4px;
    }

    .room-item {
        background: rgba(255, 255, 255, 0.04);
        padding: 14px;
        margin-bottom: 10px;
        border-radius: 12px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border: 1px solid rgba(255, 255, 255, 0.08);
        transition: all 0.18s cubic-bezier(0.25, 0.8, 0.25, 1);
        box-shadow: 0 4px 10px rgba(0,0,0,0.3), inset 0 1px 0 rgba(255,255,255,0.04);
        position: relative;
        z-index: 1;
    }

    .room-item:hover {
        background: rgba(168, 85, 247, 0.08);
        border-color: rgba(168, 85, 247, 0.3);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.35);
    }

    .room-info {
        display: flex;
        flex-direction: column;
        width: 65%;
    }

    .room-name {
        font-family: 'Press Start 2P', monospace;
        font-weight: 700;
        font-size: 16px;
        color: #ffffff;
        margin-bottom: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        text-shadow: 0 2px 4px rgba(0,0,0,0.6);
    }

    .room-status {
        font-size: 11px;
        color: #22c55e;
        font-weight: bold;
    }

    .room-status.private {
        color: #ef4444;
    }

    /* Buttons */
    .pixel-btn {
        background: linear-gradient(180deg, #22c55e 0%, #16a34a 100%);
        border: 2px solid #15803d;
        border-radius: 10px;
        box-shadow:
            inset 0 1px 0 rgba(255, 255, 255, 0.35),
            0 5px 0 #14532d;
        color: white;
        font-family: 'Press Start 2P', monospace;
        font-size: 9px;
        padding: 14px;
        width: 100%;
        text-align: center;
        cursor: pointer;
        text-transform: uppercase;
        box-sizing: border-box;
        display: block;
        margin-top: 10px;
        text-shadow: 0 1px 2px rgba(0,0,0,0.5);
        transition: all 0.12s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    .pixel-btn:hover {
        background: linear-gradient(180deg, #4ade80 0%, #22c55e 100%);
        box-shadow:
            inset 0 1px 0 rgba(255, 255, 255, 0.4),
            0 5px 0 #14532d;
        transform: translateY(-1px);
    }

    .pixel-btn:active {
        transform: translateY(4px);
        box-shadow:
            inset 0 1px 0 rgba(0,0,0,0.1),
            0 1px 0 #14532d;
    }

    .btn-small {
        background: linear-gradient(180deg, #22c55e 0%, #16a34a 100%);
        border: 2px solid #15803d;
        border-radius: 8px;
        box-shadow:
            inset 0 1px 0 rgba(255,255,255,0.35),
            0 4px 0 #14532d;
        color: white;
        font-family: 'Press Start 2P', monospace;
        font-size: 8px;
        padding: 10px 14px;
        cursor: pointer;
        text-align: center;
        text-shadow: 0 1px 2px rgba(0,0,0,0.5);
        transition: all 0.12s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    .btn-small:hover {
        background: linear-gradient(180deg, #4ade80 0%, #22c55e 100%);
        box-shadow:
            inset 0 1px 0 rgba(255,255,255,0.4),
            0 4px 0 #14532d;
        transform: translateY(-1px);
    }

    .btn-small:active {
        transform: translateY(3px);
        box-shadow:
            inset 0 1px 0 rgba(0,0,0,0.1),
            0 1px 0 #14532d;
    }

    .btn-orange {
        background: linear-gradient(180deg, #f59e0b 0%, #d97706 100%);
        border-color: #92400e;
        box-shadow:
            inset 0 1px 0 rgba(255,255,255,0.3),
            0 4px 0 #78350f;
    }

    .btn-orange:hover {
        background: linear-gradient(180deg, #fbbf24 0%, #f59e0b 100%);
        box-shadow:
            inset 0 1px 0 rgba(255,255,255,0.35),
            0 4px 0 #78350f;
        transform: translateY(-1px);
    }

    .btn-orange:active {
        transform: translateY(3px);
        box-shadow: 0 1px 0 #78350f;
    }

    .btn-red {
        background: linear-gradient(180deg, #ef4444 0%, #dc2626 100%);
        border-color: #991b1b;
        box-shadow:
            inset 0 1px 0 rgba(255,255,255,0.3),
            0 4px 0 #7f1d1d;
    }

    .btn-red:hover {
        background: linear-gradient(180deg, #f87171 0%, #ef4444 100%);
        box-shadow:
            inset 0 1px 0 rgba(255,255,255,0.35),
            0 4px 0 #7f1d1d;
        transform: translateY(-1px);
    }

    .btn-red:active {
        transform: translateY(4px);
        box-shadow: 0 1px 0 #7f1d1d;
    }

    /* Form Buat Room */
    .input-group {
        margin-bottom: 16px;
    }

    .input-label {
        font-family: 'Press Start 2P', monospace;
        font-size: 8px;
        color: #ffaa00;
        margin-bottom: 8px;
        display: block;
        letter-spacing: 0.5px;
        text-shadow: 0 1.5px 2px rgba(0,0,0,0.6);
    }

    .pixel-input {
        width: 100%;
        background: rgba(15, 23, 42, 0.6);
        border: 2px solid rgba(255, 255, 255, 0.15);
        border-radius: 8px;
        color: #ffffff;
        font-family: 'Press Start 2P', monospace;
        font-size: 15px;
        font-weight: bold;
        padding: 12px 14px;
        box-sizing: border-box;
        outline: none;
        transition: all 0.2s;
    }

    .pixel-input:focus {
        border-color: #a855f7;
        background: rgba(15, 23, 42, 0.85);
        box-shadow: none;
    }

    .pixel-input::placeholder {
        color: rgba(255, 255, 255, 0.3);
        font-weight: normal;
    }

    /* Modal Style for Password */
    #password-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.85);
        backdrop-filter: blur(8px);
        z-index: 50;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background: rgba(15, 23, 42, 0.95);
        border: 3px solid #a855f7;
        padding: 24px 20px;
        width: 85%;
        max-width: 320px;
        border-radius: 20px;
        box-sizing: border-box;
        box-shadow: 
            0 10px 30px rgba(0, 0, 0, 0.6);
        animation: modalFadeIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
    }

    @keyframes modalFadeIn {
        from {
            transform: scale(0.9);
            opacity: 0;
        }
        to {
            transform: scale(1);
            opacity: 1;
        }
    }

    /* ========= GLOBAL CHAT SIDEBAR ========= */
    #chat-toggle-btn {
        position: absolute;
        top: 64px;
        left: 0;
        width: 34px;
        height: 34px;
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border: 1.5px solid rgba(255,255,255,0.15);
        border-left: none;
        border-radius: 0 10px 10px 0;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 97;
        transition: all 0.2s ease;
        box-shadow: 3px 0 12px rgba(0,0,0,0.4);
    }
    #chat-toggle-btn:hover { background: linear-gradient(135deg,#334155 0%,#1e293b 100%); width: 38px; }
    #chat-toggle-btn .chat-icon { font-size: 16px; color: #fff; }
    #chat-unread-dot {
        position: absolute; top: 4px; right: 4px;
        width: 8px; height: 8px;
        background: #ef4444; border-radius: 50%; display: none;
    }

    #chat-backdrop {
        position: fixed;
        top: 0; left: 0;
        width: 100vw; height: 100vh;
        background: rgba(0, 0, 0, 0.45);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        z-index: 98;
        display: none;
        opacity: 0;
        transition: opacity 0.28s ease;
    }
    #chat-backdrop.show {
        display: block;
        opacity: 1;
    }

    #chat-sidebar {
        position: fixed;
        top: 0; left: 0;
        width: 260px;
        max-width: 82vw;
        height: 100%;
        max-height: 100dvh;
        background: rgba(8,15,30,0.96);
        border-right: 1px solid rgba(255,255,255,0.12);
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);
        z-index: 99;
        display: flex; flex-direction: column;
        box-shadow: 4px 0 24px rgba(0,0,0,0.6);
        transform: translateX(-100%);
        visibility: hidden;
        transition: transform 0.32s cubic-bezier(0.4,0,0.2,1), visibility 0.32s;
        box-sizing: border-box;
        overflow: hidden;
    }
    #chat-sidebar.open {
        transform: translateX(0);
        visibility: visible;
    }
    #chat-sidebar::before {
        content: ''; position: absolute; top:0; left:0; width:100%; height:100%;
        background: repeating-linear-gradient(0deg,transparent,transparent 3px,rgba(0,0,0,0.03) 3px,rgba(0,0,0,0.03) 4px);
        pointer-events: none; z-index: 0;
    }
    .chat-header {
        padding: 12px 14px 10px;
        border-bottom: 1px solid rgba(255,255,255,0.08);
        display: flex; align-items: center; justify-content: space-between;
        position: relative; z-index: 2; flex-shrink: 0;
        background: rgba(8, 15, 30, 0.98);
    }
    .chat-header-title {
        font-family: 'Ubuntu', sans-serif !important; font-size: 7px;
        color: #22c55e; letter-spacing: 0.5px;
    }
    .chat-online-badge { display:flex; align-items:center; gap:5px; font-size:10px; color:rgba(255,255,255,0.4); font-family: 'Ubuntu', sans-serif !important; }
    .chat-online-dot {
        width:6px; height:6px; border-radius:50%; background:#22c55e;
    }
    #chat-messages {
        flex: 1; overflow-y: auto; padding: 10px 12px;
        display: flex; flex-direction: column; gap: 8px;
        position: relative; z-index: 1;
        scrollbar-width: thin; scrollbar-color: rgba(34,197,94,0.3) rgba(255,255,255,0.02);
        -webkit-overflow-scrolling: touch; touch-action: pan-y;
    }
    #chat-messages::-webkit-scrollbar { width: 3px; }
    #chat-messages::-webkit-scrollbar-thumb { background: rgba(34,197,94,0.3); border-radius: 3px; }
    .chat-msg { display:flex; flex-direction:column; gap:2px; animation: msgSlideIn 0.2s ease; }
    @keyframes msgSlideIn {
        from { opacity:0; transform:translateY(6px); }
        to   { opacity:1; transform:translateY(0); }
    }
    .chat-msg.is-me { align-items: flex-end; }
    .chat-msg-name {
        font-family: 'Ubuntu', sans-serif !important; font-size: 5.5px;
        color: rgba(255,255,255,0.45); padding: 0 6px;
    }
    .chat-msg.is-me .chat-msg-name { color: rgba(34,197,94,0.7); }
    .chat-msg-bubble {
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 10px 10px 10px 2px;
        padding: 7px 10px; font-size: 12px; color: #e2e8f0;
        max-width: 86%; word-break: break-word; line-height: 1.4;
        box-shadow: 0 2px 6px rgba(0,0,0,0.3);
        font-family: 'Ubuntu', sans-serif !important;
    }
    .chat-msg.is-me .chat-msg-bubble {
        background: rgba(34,197,94,0.12); border-color: rgba(34,197,94,0.25);
        border-radius: 10px 10px 2px 10px; color: #d1fae5;
    }
    .chat-msg-bubble.room-share {
        background: rgba(251,191,36,0.08); border-color: rgba(251,191,36,0.3);
        color: #fef3c7;
    }
    .chat-msg-time { font-size: 9px; color: rgba(255,255,255,0.2); padding: 0 6px; font-family: 'Ubuntu', sans-serif !important; }
    .chat-system-msg {
        text-align: center; font-family: 'Ubuntu', sans-serif !important;
        font-size: 5.5px; color: rgba(255,255,255,0.25); padding: 4px 0;
    }
    .chat-input-area {
        padding: 10px 12px; border-top: 1px solid rgba(255,255,255,0.08);
        display: flex; gap: 7px; position: relative; z-index: 2; flex-shrink: 0;
        background: rgba(8, 15, 30, 0.98);
    }
    #chat-input {
        flex: 1; background: rgba(255,255,255,0.05);
        border: 1.5px solid rgba(255,255,255,0.12); border-radius: 8px;
        padding: 8px 10px; font-family: 'Ubuntu', sans-serif !important;
        font-size: 13px; color: #fff; outline: none; transition: all 0.2s;
    }
    #chat-input:focus {
        border-color: rgba(34,197,94,0.5); background: rgba(255,255,255,0.08);
        box-shadow: none;
    }
    #chat-input::placeholder { color: rgba(255,255,255,0.2); }
    #chat-send-btn {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        border: none; border-radius: 8px; padding: 8px 10px;
        font-size: 14px; cursor: pointer; transition: all 0.15s ease;
        flex-shrink: 0; color: #fff;
    }
    #chat-send-btn:hover { transform: translateY(-1px); }
    #chat-send-btn:active { transform: translateY(1px); }
    .chat-msg-bubble,
    #chat-input,
    #room-code-input {
        font-family: 'Ubuntu', sans-serif !important;
    }
</style>

<div id="game-ui">
    <div id="ps5-backdrop" class="ps5-backdrop-glow bg-slide-4"></div>
    <canvas id="ps5-particles"
        style="display: none; position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 1; pointer-events: none; opacity: 0.5;"></canvas>

    <div class="back-btn" onclick="window.navigateToPage('/room')">
        <img src="{{ asset_v('game_pacu/assets/image/ui/back.png') }}" alt="Back">
    </div>

    <div class="game-ui-scroll scrollable">
    <div class="title-banner">CUSTOM ROOM</div>

    <!-- Panel Buat Room -->
    <div class="panel">
        <div class="panel-title">BUAT ROOM BARU</div>
        <div class="input-group">
            <label class="input-label">Nama Room</label>
            <input type="text" id="room-name-input" class="pixel-input" placeholder="Masukkan nama room...">
        </div>
        <div class="input-group">
            <label class="input-label">Password (Opsional)</label>
            <input type="password" id="room-password-input" class="pixel-input" placeholder="Kosongkan jika publik...">
        </div>
        <button class="pixel-btn" style="margin-top: 5px;" onclick="createRoom()">BUAT & MASUK</button>
    </div>

    <!-- Panel Room Tersedia -->
    <div class="panel" style="flex: 1; max-height: 45%; min-height: 0;">
        <div class="panel-title">ROOM TERSEDIA</div>

        <!-- Cari Kode Room -->
        <div style="display:flex; gap:8px; margin-bottom:12px; position:relative; z-index:1;">
            <input
                type="text"
                id="room-code-input"
                class="pixel-input"
                placeholder="Masukkan kode room..."
                maxlength="6"
                style="flex:1; font-size:14px; letter-spacing:3px; text-transform:uppercase; padding:10px 12px; font-family:'Ubuntu',sans-serif !important;"
                oninput="this.value=this.value.toUpperCase()"
                onkeydown="if(event.key==='Enter') joinByCode()"
            >
            <button class="btn-small btn-orange" onclick="joinByCode()" style="white-space:nowrap; padding:10px 14px;">MASUK</button>
        </div>

        <div class="room-list-container scrollable" id="room-list-container">
            <div style="text-align: center; color: #ffaa00; font-size: 8px; padding: 20px; font-family: 'Press Start 2P', monospace; text-shadow: 0 1.5px 2px rgba(0,0,0,0.6);">MENCARI ROOM...</div>
        </div>
    </div>
    </div>

</div>

<!-- Password Modal -->
<div id="password-modal">
    <div class="modal-content">
        <div class="panel-title" id="modal-room-name"><i class="bi bi-box-arrow-in-right me-1"></i> MASUK ROOM</div>
        <div class="input-group" style="margin-top: 20px;">
            <label class="input-label">Masukkan Password</label>
            <input type="password" class="pixel-input" id="join-password" placeholder="Password...">
        </div>
        <div style="display: flex; gap: 10px; margin-top: 20px;">
            <button class="pixel-btn btn-red" style="margin-top: 0;" onclick="hidePasswordModal()">BATAL</button>
            <button class="pixel-btn" style="margin-top: 0;" onclick="submitPassword()">MASUK</button>
        </div>
    </div>
</div>

<!-- ===== GLOBAL CHAT SIDEBAR ===== -->
<div id="chat-backdrop" onclick="handleChatBackdropClick(event)"></div>
<div id="chat-toggle-btn" onclick="toggleChat()">
    <span class="chat-icon"><i class="bi bi-chat-dots-fill"></i></span>
    <span id="chat-unread-dot"></span>
</div>
<div id="chat-sidebar">
    <div class="chat-header">
        <div class="chat-header-title"><i class="bi bi-chat-fill me-1"></i> GLOBAL CHAT</div>
        <div style="display: flex; align-items: center; gap: 8px;">
            <div class="chat-online-badge">
                <span class="chat-online-dot"></span>
                <span id="chat-online-count">0</span> online
            </div>
            <button onclick="toggleChat()" style="background: none; border: none; color: rgba(255,255,255,0.6); font-size: 14px; font-weight: bold; cursor: pointer; padding: 0 4px; line-height: 1;" title="Tutup"><i class="bi bi-x-lg"></i></button>
        </div>
    </div>
    <div id="chat-messages" class="scrollable">
        <div class="chat-system-msg">” Global Chat ”</div>
    </div>
    <div class="chat-input-area">
        <input type="text" id="chat-input" placeholder="Ketik pesan..." maxlength="200" onkeydown="if(event.key==='Enter') sendChat()">
        <button id="chat-send-btn" onclick="sendChat()" title="Kirim"><i class="bi bi-send-fill"></i></button>
    </div>
</div>
@endsection

@push('scripts')
<script>
{
    // Custom HTML Modals (Confirm & Alert) using existing game-layout.css styles
    window.showHTMLAlert = function(message, title = "INFORMASI") {
        return new Promise((resolve) => {
            const overlay = document.createElement('div');
            overlay.id = 'fullscreen-modal-overlay';
            overlay.innerHTML = `
                <div class="fullscreen-modal-card">
                    <div class="fullscreen-modal-title">${title}</div>
                    <div class="fullscreen-modal-body">${message}</div>
                    <div class="fullscreen-modal-buttons">
                        <button class="fullscreen-btn fullscreen-btn-yes" id="custom-alert-ok-btn" style="width: 120px;">OKE</button>
                    </div>
                </div>
            `;
            document.body.appendChild(overlay);
            overlay.offsetHeight;
            overlay.classList.add('show');
            const okBtn = document.getElementById('custom-alert-ok-btn');
            okBtn.addEventListener('click', () => {
                overlay.classList.remove('show');
                setTimeout(() => {
                    overlay.remove();
                    resolve();
                }, 300);
            });
        });
    };

    window.showHTMLConfirm = function(message, title = "âœ¦ KONFIRMASI âœ¦") {
        return new Promise((resolve) => {
            const overlay = document.createElement('div');
            overlay.id = 'fullscreen-modal-overlay';
            overlay.innerHTML = `
                <div class="fullscreen-modal-card">
                    <div class="fullscreen-modal-title">${title}</div>
                    <div class="fullscreen-modal-body">${message}</div>
                    <div class="fullscreen-modal-buttons">
                        <button class="fullscreen-btn fullscreen-btn-yes" id="custom-confirm-yes-btn" style="width: 100px;">YA</button>
                        <button class="fullscreen-btn fullscreen-btn-no" id="custom-confirm-no-btn" style="width: 100px;">TIDAK</button>
                    </div>
                </div>
            `;
            document.body.appendChild(overlay);
            overlay.offsetHeight;
            overlay.classList.add('show');
            const yesBtn = document.getElementById('custom-confirm-yes-btn');
            const noBtn = document.getElementById('custom-confirm-no-btn');
            yesBtn.addEventListener('click', () => {
                overlay.classList.remove('show');
                setTimeout(() => {
                    overlay.remove();
                    resolve(true);
                }, 300);
            });
            noBtn.addEventListener('click', () => {
                overlay.classList.remove('show');
                setTimeout(() => {
                    overlay.remove();
                    resolve(false);
                }, 300);
            });
        });
    };

    let selectedRoomId = null;

    window.showPasswordModal = function(roomId, roomName) {
        selectedRoomId = roomId;
        document.getElementById('modal-room-name').innerText = 'âœ¦ ROOM: ' + roomName.toUpperCase() + ' âœ¦';
        document.getElementById('join-password').value = '';
        document.getElementById('password-modal').style.display = 'flex';
    };

    window.hidePasswordModal = function() {
        document.getElementById('password-modal').style.display = 'none';
        selectedRoomId = null;
    };

    window.createRoom = function() {
        const name = document.getElementById('room-name-input').value.trim();
        const password = document.getElementById('room-password-input').value;

        if (!name) {
            showHTMLAlert('Silakan masukkan nama room!');
            return;
        }

        fetch('/room/create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ name, password })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success && data.redirect_url) {
                window.navigateToPage(data.redirect_url);
            } else {
                showHTMLAlert(data.message || 'Gagal membuat room.');
            }
        })
        .catch(err => {
            console.error(err);
            showHTMLAlert('Terjadi kesalahan koneksi.');
        });
    };

    window.joinByCode = function() {
        const code = document.getElementById('room-code-input').value.trim().toUpperCase();
        if (!code || code.length < 4) {
            showHTMLAlert('Masukkan kode room yang valid!');
            return;
        }

        fetch('/room/join-by-code', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ room_code: code })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success && data.redirect_url) {
                window.navigateToPage(data.redirect_url);
            } else if (data.requires_password) {
                showPasswordModal(data.room_id, data.room_name);
            } else {
                showHTMLAlert(data.message || 'Kode room tidak ditemukan.');
            }
        })
        .catch(err => {
            console.error(err);
            showHTMLAlert('Terjadi kesalahan koneksi.');
        });
    };

    window.joinRoom = function(roomId, password = null) {
        fetch('/room/join', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ room_id: roomId, password: password })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success && data.redirect_url) {
                window.navigateToPage(data.redirect_url);
            } else {
                showHTMLAlert(data.message || 'Gagal masuk ke room.');
            }
        })
        .catch(err => {
            console.error(err);
            showHTMLAlert('Terjadi kesalahan koneksi.');
        });
    };

    window.submitPassword = function() {
        const pwd = document.getElementById('join-password').value;
        if (pwd.trim() === '') {
            showHTMLAlert('Silakan masukkan password!');
            return;
        }
        if (selectedRoomId) {
            joinRoom(selectedRoomId, pwd);
            hidePasswordModal();
        }
    };

    window.fetchRooms = function() {
        // Prevent API polling if the element is not present (navigated away)
        const container = document.getElementById('room-list-container');
        if (!container) return;

        fetch('/room/list')
        .then(res => res.json())
        .then(data => {
            const container = document.getElementById('room-list-container');
            if (!container) return;
            container.innerHTML = '';
            
            if (!data.rooms || data.rooms.length === 0) {
                container.innerHTML = '<div style="text-align: center; color: #ffaa00; font-size: 8px; padding: 20px; font-family: \'Press Start 2P\', monospace; text-shadow: 0 1.5px 2px rgba(0,0,0,0.6);">BELUM ADA ROOM</div>';
                return;
            }

            data.rooms.forEach(room => {
                const item = document.createElement('div');
                item.className = 'room-item';
                
                const info = document.createElement('div');
                info.className = 'room-info';
                
                const nameSpan = document.createElement('span');
                nameSpan.className = 'room-name';
                nameSpan.innerText = room.name;
                
                const statusSpan = document.createElement('span');
                statusSpan.className = 'room-status' + (room.is_private ? ' private' : '');
                statusSpan.innerText = (room.is_private ? 'Privat' : 'Publik') + ' (Host: ' + room.host_name + ')';
                
                info.appendChild(nameSpan);
                info.appendChild(statusSpan);
                
                const button = document.createElement('button');
                if (room.is_private) {
                    button.className = 'btn-small btn-orange';
                    button.innerText = 'PASSWORD';
                    button.onclick = () => showPasswordModal(room.id, room.name);
                } else {
                    button.className = 'btn-small';
                    button.innerText = 'JOIN';
                    button.onclick = () => joinRoom(room.id);
                }
                
                item.appendChild(info);
                item.appendChild(button);
                
                container.appendChild(item);
            });
        })
        .catch(err => console.error('Gagal mengambil daftar room:', err));
    };

    // Initial load
    fetchRooms();

    // Poll rooms every 4 seconds
    const fetchRoomsInterval = setInterval(fetchRooms, 4000);

    // ============= GLOBAL CHAT =============
    const chatCurrentUserId = {{ auth()->id() }};
    const chatCurrentUser   = "{{ addslashes(auth()->user()->nama_jalur ?? auth()->user()->email) }}";
    let chatWs     = null;
    let chatOpen   = false;
    let chatUnread = 0;
    const MAX_CHAT_MESSAGES = 80;

    window.initGlobalChat = function() {
        if (chatWs) return;
        if (!document.getElementById('room-list-container')) return;

        const protocol = window.location.protocol === 'https:' ? 'wss:' : 'ws:';
        let wsUrl;
        if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1' || window.location.hostname.startsWith('192.168.')) {
            wsUrl = `${protocol}//${window.location.hostname}:8080`;
        } else {
            wsUrl = `${protocol}//${window.location.hostname}/ws`;
        }
        chatWs = new WebSocket(wsUrl);

        chatWs.onopen = () => {
            if (chatWs) {
                chatWs.send(JSON.stringify({
                    type: 'join',
                    roomId: 'global_chat',
                    payload: { userId: chatCurrentUserId, userName: chatCurrentUser, customizations: {} }
                }));
            }
        };

        chatWs.onmessage = (event) => {
            try {
                const data = JSON.parse(event.data);
                if (data.type === 'global_chat') {
                    appendChatMessage(data.payload);
                } else if (data.type === 'chat_history') {
                    const container = document.getElementById('chat-messages');
                    if (container && Array.isArray(data.payload) && data.payload.length > 0) {
                        container.innerHTML = '';
                        data.payload.forEach(msg => appendChatMessage(msg));
                    }
                } else if (data.type === 'room_update' && data.payload && data.payload.players) {
                    const el = document.getElementById('chat-online-count');
                    if (el) el.textContent = data.payload.players.length;
                }
            } catch (e) {}
        };

        chatWs.onclose = () => {
            chatWs = null;
            if (document.getElementById('room-list-container')) {
                setTimeout(initGlobalChat, 3000);
            }
        };
        chatWs.onerror = () => {};
    };

    window.appendChatMessage = function(payload) {
        const container = document.getElementById('chat-messages');
        if (!container) return;
        const isMe = parseInt(payload.userId) === chatCurrentUserId;
        const d = new Date(payload.timestamp || Date.now());
        const hh = String(d.getHours()).padStart(2, '0');
        const mm = String(d.getMinutes()).padStart(2, '0');
        const isRoomShare = payload.message && String(payload.message).includes('ROOM:') && String(payload.message).includes('KODE:');

        const msgEl = document.createElement('div');
        msgEl.className = 'chat-msg' + (isMe ? ' is-me' : '');
        msgEl.innerHTML = `
            <div class="chat-msg-name">${escapeHTML(payload.userName)}</div>
            <div class="chat-msg-bubble${isRoomShare ? ' room-share' : ''}">${escapeHTML(payload.message)}</div>
            <div class="chat-msg-time">${hh}:${mm}</div>
        `;
        container.appendChild(msgEl);
        while (container.children.length > MAX_CHAT_MESSAGES) container.removeChild(container.firstChild);
        container.scrollTop = container.scrollHeight;

        if (!chatOpen && !isMe) {
            chatUnread++;
            const dot = document.getElementById('chat-unread-dot');
            if (dot) dot.style.display = 'block';
        }
    };

    function adaptChatSidebarViewport() {
        const sidebar = document.getElementById('chat-sidebar');
        if (!sidebar || !chatOpen) return;
        if (window.visualViewport) {
            sidebar.style.height = window.visualViewport.height + 'px';
            sidebar.style.top = window.visualViewport.offsetTop + 'px';
        }
        window.scrollTo(0, 0);
    }

    if (window.visualViewport) {
        window.visualViewport.addEventListener('resize', adaptChatSidebarViewport);
        window.visualViewport.addEventListener('scroll', adaptChatSidebarViewport);
    }

    window.handleChatBackdropClick = function(e) {
        const inp = document.getElementById('chat-input');
        if (inp && document.activeElement === inp) {
            inp.blur();
            return;
        }
        toggleChat();
    };

    window.sendChat = function() {
        const input = document.getElementById('chat-input');
        if (!input) return;
        const msg = input.value.trim();
        if (!msg || !chatWs || chatWs.readyState !== WebSocket.OPEN) return;
        chatWs.send(JSON.stringify({
            type: 'global_chat',
            roomId: 'global_chat',
            payload: { userId: chatCurrentUserId, userName: chatCurrentUser, message: msg }
        }));
        input.value = '';
        chatOpen = true;
        const container = document.getElementById('chat-messages');
        if (container) container.scrollTop = container.scrollHeight;
    };

    window.toggleChat = function() {
        chatOpen = !chatOpen;
        const sidebar = document.getElementById('chat-sidebar');
        const backdrop = document.getElementById('chat-backdrop');
        const inp = document.getElementById('chat-input');
        if (!sidebar) return;
        if (chatOpen) {
            sidebar.classList.add('open');
            if (backdrop) backdrop.classList.add('show');
            chatUnread = 0;
            const dot = document.getElementById('chat-unread-dot');
            if (dot) dot.style.display = 'none';
            adaptChatSidebarViewport();
            setTimeout(() => { if (inp) inp.focus(); }, 300);
            const msgs = document.getElementById('chat-messages');
            if (msgs) msgs.scrollTop = msgs.scrollHeight;
        } else {
            sidebar.classList.remove('open');
            if (backdrop) backdrop.classList.remove('show');
            if (inp) inp.blur();
            if (document.activeElement) document.activeElement.blur();
            sidebar.style.height = '100%';
            sidebar.style.top = '0';
            setTimeout(() => {
                window.scrollTo({ top: 0, left: 0, behavior: 'instant' });
                document.body.scrollTop = 0;
                document.documentElement.scrollTop = 0;
            }, 50);
        }
    };

    window.escapeHTML = function(str) {
        return String(str)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;').replace(/'/g, '&#39;');
    };

    const chatClickOutside = function(e) {
        if (!chatOpen) return;
        const sidebar = document.getElementById('chat-sidebar');
        const btn = document.getElementById('chat-toggle-btn');
        const inp = document.getElementById('chat-input');

        if ((sidebar && sidebar.contains(e.target)) || (btn && btn.contains(e.target))) {
            return;
        }

        if (inp && document.activeElement === inp) {
            inp.blur();
            return;
        }

        toggleChat();
    };
    document.addEventListener('click', chatClickOutside);

    const chatInputEl = document.getElementById('chat-input');
    if (chatInputEl) {
        chatInputEl.addEventListener('focus', function() {
            setTimeout(adaptChatSidebarViewport, 100);
        });
        chatInputEl.addEventListener('blur', function() {
            setTimeout(() => {
                const sidebar = document.getElementById('chat-sidebar');
                if (sidebar && !sidebar.classList.contains('open')) {
                    sidebar.style.height = '100%';
                    sidebar.style.top = '0';
                } else {
                    adaptChatSidebarViewport();
                }
                window.scrollTo({ top: 0, left: 0, behavior: 'instant' });
                document.body.scrollTop = 0;
                document.documentElement.scrollTop = 0;
            }, 100);
        });
    }

    function initCreateOrJoinChat() {
        if (!document.getElementById('room-list-container')) return;
        initGlobalChat();
    }

    initCreateOrJoinChat();
    document.addEventListener('game:page-ready', initCreateOrJoinChat);

    // Clean up interval on navigation to avoid memory leaks
    document.addEventListener('livewire:navigating', () => {
        clearInterval(fetchRoomsInterval);
        document.removeEventListener('click', chatClickOutside);
        if (chatWs) {
            chatWs.close();
            chatWs = null;
            console.log('Create/Join global chat WebSocket closed.');
        }
        console.log('Room list polling stopped.');
    });
}
</script>
@endpush
