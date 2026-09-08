@extends('layouts.game')

@section('title', 'Pacu Jalur: The Pixel ” Menu Utama')

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
        justify-content: center;
        background: url('/game_pacu/assets/image/bg/bgmenu.jpg') no-repeat center center;
        background-size: cover;
        z-index: 10;
        padding-bottom: 20px;
        box-sizing: border-box;
        overflow: hidden;
    }

    .menu-main-wrapper {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        margin: auto 0;
        width: 100%;
        max-width: 360px;
        z-index: 12;
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

    .bg-slide-0 {
        background: rgba(6, 17, 10, 0.9);
    }

    .bg-slide-1 {
        background: rgba(15, 5, 20, 0.9);
    }

    .bg-slide-2 {
        background: rgba(20, 10, 5, 0.9);
    }

    .bg-slide-3 {
        background: rgba(20, 5, 5, 0.9);
    }

    .bg-slide-4 {
        background: rgba(18, 15, 5, 0.9);
    }

    .bg-slide-5 {
        background: rgba(5, 10, 20, 0.9);
    }

    /* --- PS5 Top Header Layout (Reverted to Flat Retro Theme) --- */
    .profile-btn {
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

    .profile-btn img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        image-rendering: pixelated;
    }

    .profile-btn:hover {
        transform: scale(1.05);
    }

    .profile-btn:active {
        transform: scale(0.9);
    }

    .sound-btn {
        position: absolute;
        top: 16px;
        left: 50%;
        transform: translateX(-50%);
        width: 36px;
        height: 36px;
        background: none;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 15;
        transition: filter 0.15s ease, opacity 0.15s ease;
        box-sizing: border-box;
    }

    .sound-btn img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        image-rendering: pixelated;
        pointer-events: none;
    }

    .sound-btn:hover {
        filter: brightness(1.3);
    }

    .sound-btn:active {
        filter: brightness(0.75);
        opacity: 0.85;
    }

    .coin-display {
        position: absolute;
        top: 16px;
        right: 14px;
        height: 36px;
        display: flex;
        align-items: center;
        gap: 6px;
        z-index: 15;
        box-sizing: border-box;
    }

    .coin-icon-wrapper {
        position: relative;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .coin-icon-wrapper img {
        width: 100%;
        height: 100%;
        image-rendering: pixelated;
    }

    .coin-icon-wrapper::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: none;
        display: none;
    }

    #header-coin-count {
        font-family: 'Press Start 2P', monospace;
        font-size: 8px;
        font-weight: bold;
        color: #000000;
        text-shadow:
            1px 1px 0px #ffffff,
            -1px -1px 0px #ffffff,
            1px -1px 0px #ffffff,
            -1px 1px 0px #ffffff,
            0px 1px 0px #ffffff,
            0px -1px 0px #ffffff,
            1px 0px 0px #ffffff,
            -1px 0px 0px #ffffff;
        line-height: 1;
    }

    @keyframes htmlShimmer {
        0% {
            transform: translateX(-150%) skewX(-25deg);
        }

        100% {
            transform: translateX(150%) skewX(-25deg);
        }
    }

    .title-banner {
        font-family: 'Press Start 2P', monospace;
        font-size: 12px;
        background: linear-gradient(180deg, #ffffff 0%, #a5f3fc 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-top: 0;
        margin-bottom: 12px;
        text-align: center;
        line-height: 1.4;
        letter-spacing: 2px;
        z-index: 11;
    }

    

    /* --- PS5 Carousel Slider --- */
    .ps5-carousel-container {
        position: relative;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 5px;
        margin-bottom: 10px;
        z-index: 12;
    }

    .ps5-carousel-view {
        width: 100%;
        max-width: 300px;
        height: 155px;
        overflow: visible;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: flex-start;
    }

    .ps5-carousel-track {
        display: flex;
        align-items: center;
        gap: 20px;
        transition: transform 0.35s ease;
        will-change: transform;
    }

    .ps5-carousel-container .ps5-card {
        width: 110px;
        height: 135px;
        flex-shrink: 0;
        border-radius: 16px;
        border: 2px solid rgba(255, 255, 255, 0.15);
        background: rgba(15, 23, 42, 0.85);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 12px;
        box-sizing: border-box;
        cursor: pointer;
        position: relative;
        transition: transform 0.3s ease, opacity 0.3s ease, border-color 0.3s ease;
        opacity: 0.5;
        transform: scale(0.85);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
        overflow: hidden;
    }

    .ps5-carousel-container .ps5-card.active {
        opacity: 1;
        transform: scale(1.1);
        border-color: #ffffff;
        background: rgba(30, 41, 59, 0.95);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.7);
    }

    .ps5-carousel-container .ps5-card.card-green {
        --glow-color: rgba(34, 197, 94, 0.6);
    }

    .ps5-carousel-container .ps5-card.card-red {
        --glow-color: rgba(239, 68, 68, 0.6);
    }

    .ps5-carousel-container .ps5-card.card-orange {
        --glow-color: rgba(249, 115, 22, 0.6);
    }

    .ps5-carousel-container .ps5-card.card-yellow {
        --glow-color: rgba(234, 179, 8, 0.6);
    }

    .ps5-carousel-container .ps5-card.card-purple {
        --glow-color: rgba(168, 85, 247, 0.6);
    }

    .ps5-card.card-blue {
        --glow-color: rgba(59, 130, 246, 0.6);
    }

    .ps5-card-icon {
        width: 52px;
        height: 52px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 10px;
        transition: transform 0.3s ease;
    }

    .ps5-card.active .ps5-card-icon {
        transform: translateY(-4px) scale(1.08);
    }

    .ps5-card-icon img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        image-rendering: pixelated;
    }

    .ps5-card-label {
        font-family: 'Press Start 2P', monospace;
        font-size: 8px;
        text-align: center;
        color: #ffffff;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.8);
        line-height: 1.4;
        pointer-events: none;
        font-weight: bold;
    }

    .ps5-pattern {
        position: absolute;
        bottom: 4px;
        right: 6px;
        font-family: 'Press Start 2P', monospace;
        font-size: 20px;
        font-weight: bold;
        color: rgba(255, 255, 255, 0.04);
        pointer-events: none;
        user-select: none;
        line-height: 1;
    }

    .ps5-card.active .ps5-pattern {
        color: rgba(255, 255, 255, 0.1);
    }

    .carousel-nav-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 13;
        transition: all 0.2s cubic-bezier(0.34, 1.56, 0.64, 1);
        padding: 0;
        box-shadow: none;
    }

    .carousel-nav-btn img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        image-rendering: pixelated;
    }

    .carousel-nav-btn:hover {
        transform: translateY(-50%) scale(1.15);
    }

    .carousel-nav-btn:active {
        transform: translateY(-50%) scale(0.9);
    }

    .prev-btn {
        left: 4px;
    }

    .next-btn {
        right: 4px;
    }

    /* --- Slide details & Controller CTA button --- */
    .ps5-details-container {
        width: 90%;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        z-index: 12;
        margin-top: 2px;
    }

    .ps5-details-title {
        font-family: 'Press Start 2P', monospace;
        font-size: 11px;
        letter-spacing: 1px;
        color: #ffffff;
        margin-bottom: 6px;
        text-shadow: 0 0 10px var(--glow-color);
        transition: text-shadow 0.3s;
    }

    .ps5-details-desc {
        font-family: 'Press Start 2P', monospace;
        font-size: 12px;
        color: rgba(255, 255, 255, 0.8);
        margin-bottom: 12px;
        height: 34px;
        line-height: 1.4;
        max-width: 250px;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
    }

    .ps5-indicators {
        display: flex;
        gap: 8px;
        margin-bottom: 14px;
    }

    .ps5-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.25);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .ps5-dot.active {
        background: #ffffff;
        transform: scale(1.25);
        box-shadow: 0 0 8px #ffffff;
    }

    .pixel-btn {
        background: linear-gradient(180deg, #22c55e 0%, #16a34a 100%);
        border: 2px solid #ffffff;
        border-radius: 10px;
        box-shadow:
            inset 0 1px 0 rgba(255, 255, 255, 0.35),
            0 5px 0 #14532d,
            0 6px 14px rgba(34, 197, 94, 0.35);
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
        letter-spacing: 0.5px;
    }

    .pixel-btn:hover {
        background: linear-gradient(180deg, #4ade80 0%, #22c55e 100%);
        box-shadow:
            inset 0 1px 0 rgba(255, 255, 255, 0.4),
            0 5px 0 #14532d,
            0 8px 20px rgba(34, 197, 94, 0.45);
        transform: translateY(-1px);
    }

    .pixel-btn:active {
        transform: translateY(4px);
        box-shadow:
            inset 0 1px 0 rgba(255, 255, 255, 0.15),
            0 1px 0 #14532d,
            0 2px 6px rgba(34, 197, 94, 0.2);
    }

    /* Loading Overlay */
    .loading-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.9);
        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        z-index: 30;
    }

    .matchmaking-radar-container {
        position: relative;
        width: 120px;
        height: 120px;
        margin-bottom: 40px;
    }

    /* Radar sweep */
    .radar {
        position: absolute;
        top: 10px;
        left: 10px;
        width: 100px;
        height: 100px;
        border-radius: 50%;
        border: 3px solid rgba(34, 197, 94, 0.4);
        box-shadow: 0 0 15px rgba(34, 197, 94, 0.3), inset 0 0 15px rgba(34, 197, 94, 0.2);
        overflow: hidden;
        background: rgba(34, 197, 94, 0.1);
    }

    .radar::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 50px;
        height: 50px;
        background: linear-gradient(45deg, rgba(34, 197, 94, 0.9) 0%, transparent 60%);
        transform-origin: 0 0;
        animation: radarSweep 1.5s linear infinite;
    }

    @keyframes radarSweep {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .magnifying-glass {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        animation: searchHover 3s cubic-bezier(0.4, 0, 0.2, 1) infinite;
        z-index: 2;
    }

    .lens {
        width: 44px;
        height: 44px;
        border: 6px solid #ffffff;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        position: absolute;
        top: 20px;
        left: 20px;
        box-shadow: inset 4px 4px 0 rgba(255, 255, 255, 0.6), 0 6px 12px rgba(0, 0, 0, 0.4);
    }

    .handle {
        width: 12px;
        height: 35px;
        background: #d97706;
        position: absolute;
        top: 60px;
        left: 60px;
        transform: rotate(-45deg);
        transform-origin: top left;
        border-radius: 6px;
        border: 3px solid #78350f;
        box-shadow: inset -2px -2px 0 rgba(0, 0, 0, 0.3), 2px 2px 5px rgba(0, 0, 0, 0.4);
    }

    @keyframes searchHover {
        0% { transform: translate(0px, 0px) scale(1) rotate(0deg); }
        33% { transform: translate(25px, -15px) scale(1.1) rotate(15deg); }
        66% { transform: translate(-15px, 20px) scale(0.95) rotate(-10deg); }
        100% { transform: translate(0px, 0px) scale(1) rotate(0deg); }
    }

    .loading-text {
        font-family: 'Press Start 2P', monospace;
        font-size: 14px;
        color: #22c55e;
        -webkit-text-stroke: 1px #ffffff;
        text-shadow: 2px 2px 0 rgba(0, 0, 0, 0.6);
        animation: pulseText 1.5s infinite;
        text-align: center;
        line-height: 1.6;
        margin-bottom: 10px;
    }

    @keyframes pulseText {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.05); opacity: 0.8; }
    }

    .btn-cancel {
        background: linear-gradient(180deg, #ef4444 0%, #dc2626 100%);
        border-color: #991b1b;
        box-shadow:
            inset 0 1px 0 rgba(255,255,255,0.3),
            0 5px 0 #7f1d1d,
            0 6px 14px rgba(239, 68, 68, 0.35);
        width: auto;
        font-size: 10px;
        padding: 12px 25px;
        margin-top: 30px;
        text-shadow: 0 1px 2px rgba(0,0,0,0.5);
    }

    .btn-cancel:hover {
        background: linear-gradient(180deg, #f87171 0%, #ef4444 100%);
        box-shadow:
            inset 0 1px 0 rgba(255,255,255,0.35),
            0 5px 0 #7f1d1d,
            0 8px 20px rgba(239, 68, 68, 0.45);
        transform: translateY(-1px);
    }

    .btn-cancel:active {
        transform: translateY(4px);
        box-shadow: 0 1px 0 #7f1d1d;
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

    #chat-toggle-btn:hover {
        background: linear-gradient(135deg, #334155 0%, #1e293b 100%);
        width: 40px;
    }

    #chat-toggle-btn .chat-icon { font-size: 16px; }

    #chat-unread-dot {
        position: absolute;
        top: 4px; right: 4px;
        width: 8px; height: 8px;
        background: #ef4444;
        border-radius: 50%;
        display: none;
        animation: dotPulse 1s infinite ease-in-out;
    }

    @keyframes dotPulse {
        0%,100% { transform: scale(1); opacity: 1; }
        50%      { transform: scale(1.4); opacity: 0.7; }
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
        background: rgba(8, 15, 30, 0.96);
        border-right: 1px solid rgba(255,255,255,0.12);
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);
        z-index: 99;
        display: flex;
        flex-direction: column;
        box-shadow: 4px 0 24px rgba(0,0,0,0.6);
        transform: translateX(-100%);
        visibility: hidden;
        transition: transform 0.32s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.32s;
        box-sizing: border-box;
        overflow: hidden;
    }

    #chat-sidebar.open {
        transform: translateX(0);
        visibility: visible;
    }

    #chat-sidebar::before {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 100%; height: 100%;
        background: repeating-linear-gradient(
            0deg, transparent, transparent 3px,
            rgba(0,0,0,0.03) 3px, rgba(0,0,0,0.03) 4px
        );
        pointer-events: none;
        z-index: 0;
    }

    .chat-header {
        padding: 12px 14px 10px;
        border-bottom: 1px solid rgba(255,255,255,0.08);
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
        z-index: 2;
        flex-shrink: 0;
        background: rgba(8, 15, 30, 0.98);
    }

    .chat-header-title {
         font-family: 'Ubuntu', sans-serif !important;
        font-size: 7px;
        color: #22c55e;
        text-shadow: 0 0 8px rgba(34,197,94,0.5);
        letter-spacing: 0.5px;
    }

    .chat-online-badge {
        display: flex;
        align-items: center;
        gap: 5px;
        font-size: 10px;
        color: rgba(255,255,255,0.4);
    }

    .chat-online-dot {
        width: 6px; height: 6px;
        border-radius: 50%;
        background: #22c55e;
        box-shadow: 0 0 5px rgba(34,197,94,0.7);
        animation: dotPulse 2s infinite;
    }

    #chat-messages {
        flex: 1;
        overflow-y: auto;
        padding: 10px 12px;
        display: flex;
        flex-direction: column;
        gap: 8px;
        position: relative;
        z-index: 1;
        scrollbar-width: thin;
        scrollbar-color: rgba(34,197,94,0.3) rgba(255,255,255,0.02);
        -webkit-overflow-scrolling: touch;
        touch-action: pan-y;
    }

    #chat-messages::-webkit-scrollbar { width: 3px; }
    #chat-messages::-webkit-scrollbar-thumb {
        background: rgba(34,197,94,0.3);
        border-radius: 3px;
    }

    .chat-msg {
        display: flex;
        flex-direction: column;
        gap: 2px;
        animation: msgSlideIn 0.2s ease;
    }

    @keyframes msgSlideIn {
        from { opacity: 0; transform: translateY(6px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .chat-msg.is-me { align-items: flex-end; }

    .chat-msg-name {
         font-family: 'Ubuntu', sans-serif !important;
        font-size: 5.5px;
        color: rgba(255,255,255,0.45);
        padding: 0 6px;
    }

    .chat-msg.is-me .chat-msg-name { color: rgba(34,197,94,0.7); }

    .chat-msg-bubble {
        background: rgba(255,255,255,0.06);
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 10px 10px 10px 2px;
        padding: 7px 10px;
        font-size: 12px;
        color: #e2e8f0;
        max-width: 86%;
        word-break: break-word;
        line-height: 1.4;
        box-shadow: 0 2px 6px rgba(0,0,0,0.3);
        font-family: 'Ubuntu', sans-serif !important;
    }

    .chat-msg.is-me .chat-msg-bubble {
        background: rgba(34,197,94,0.12);
        border-color: rgba(34,197,94,0.25);
        border-radius: 10px 10px 2px 10px;
        color: #d1fae5;
    }

    .chat-msg-bubble.room-share {
        background: rgba(251,191,36,0.08);
        border-color: rgba(251,191,36,0.3);
        color: #fef3c7;
    }

    .chat-msg-time {
        font-size: 9px;
        color: rgba(255,255,255,0.2);
        padding: 0 6px;
    }

    .chat-system-msg {
        text-align: center;
         font-family: 'Ubuntu', sans-serif !important;
        font-size: 5.5px;
        color: rgba(255,255,255,0.25);
        padding: 4px 0;
    }

    .chat-input-area {
        padding: 10px 12px;
        border-top: 1px solid rgba(255,255,255,0.08);
        display: flex;
        gap: 7px;
        position: relative;
        z-index: 2;
        flex-shrink: 0;
        background: rgba(8, 15, 30, 0.98);
    }

    #chat-input {
        flex: 1;
        background: rgba(255,255,255,0.05);
        border: 1.5px solid rgba(255,255,255,0.12);
        border-radius: 8px;
        padding: 8px 10px;
         font-family: 'Ubuntu', sans-serif !important;
        font-size: 13px;
        color: #ffffff;
        outline: none;
        transition: all 0.2s;
    }

    #chat-input:focus {
        border-color: rgba(34,197,94,0.5);
        background: rgba(255,255,255,0.08);
        box-shadow: 0 0 8px rgba(34,197,94,0.15);
    }

    #chat-input::placeholder { color: rgba(255,255,255,0.2); }

    #chat-send-btn {
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        border: none;
        border-radius: 8px;
        padding: 8px 10px;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.15s ease;
        flex-shrink: 0;
        box-shadow: 0 3px 8px rgba(34,197,94,0.3);
    }

    #chat-send-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 5px 12px rgba(34,197,94,0.4);
    }

    #chat-send-btn:active { transform: translateY(1px); }

    /* ========= INBOX SIDEBAR (RIGHT TOGGLE) ========= */
    #inbox-toggle-btn {
        position: absolute;
        color: #ffffff;
        top: 64px;
        right: 0;
        width: 34px;
        height: 34px;
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border: 1.5px solid rgba(255,255,255,0.15);
        border-right: none;
        border-radius: 10px 0 0 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 97;
        transition: all 0.2s ease;
        box-shadow: -3px 0 12px rgba(0,0,0,0.4);
    }

    #inbox-toggle-btn:hover {
        background: linear-gradient(135deg, #334155 0%, #1e293b 100%);
        width: 40px;
    }

    #inbox-toggle-btn .inbox-icon { font-size: 16px; color: #38bdf8; }

    #inbox-unread-dot {
        position: absolute;
        top: 4px; left: 4px;
        width: 8px; height: 8px;
        background: #ef4444;
        border-radius: 50%;
        display: none;
        animation: dotPulse 1s infinite ease-in-out;
    }

    #inbox-backdrop {
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
    #inbox-backdrop.show {
        display: block;
        opacity: 1;
    }

    #inbox-sidebar {
        position: fixed;
        top: 0; right: 0;
        width: 270px;
        max-width: 85vw;
        height: 100%;
        max-height: 100dvh;
        background: rgba(8, 15, 30, 0.96);
        border-left: 1px solid rgba(255,255,255,0.12);
        backdrop-filter: blur(18px);
        -webkit-backdrop-filter: blur(18px);
        z-index: 99;
        display: flex;
        flex-direction: column;
        box-shadow: -4px 0 24px rgba(0,0,0,0.6);
        transform: translateX(100%);
        visibility: hidden;
        transition: transform 0.32s cubic-bezier(0.4, 0, 0.2, 1), visibility 0.32s;
        box-sizing: border-box;
        overflow: hidden;
    }

    #inbox-sidebar.open {
        transform: translateX(0);
        visibility: visible;
    }

    .inbox-header {
        padding: 12px 14px 10px;
        border-bottom: 1px solid rgba(255,255,255,0.08);
        display: flex;
        align-items: center;
        justify-content: space-between;
        position: relative;
        z-index: 2;
        flex-shrink: 0;
        background: rgba(8, 15, 30, 0.98);
    }

    .inbox-header-title {
         font-family: 'Ubuntu', sans-serif !important;
        font-size: 7px;
        color: #38bdf8;
        text-shadow: 0 0 8px rgba(56,189,248,0.5);
        letter-spacing: 0.5px;
    }

    #inbox-messages-list {
        flex: 1;
        overflow-y: auto;
        padding: 10px 12px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        position: relative;
        z-index: 1;
        scrollbar-width: thin;
        scrollbar-color: rgba(56,189,248,0.3) rgba(255,255,255,0.02);
        -webkit-overflow-scrolling: touch;
        touch-action: pan-y;
    }

    .inbox-card {
        background: rgba(255,255,255,0.05);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 10px;
        padding: 10px;
        display: flex;
        flex-direction: column;
        gap: 6px;
        transition: all 0.2s ease;
    }

    .inbox-card.unread {
        background: rgba(56,189,248,0.08);
        border-color: rgba(56,189,248,0.3);
        box-shadow: 0 2px 8px rgba(56,189,248,0.15);
    }

    .inbox-card-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 6px;
    }

    .inbox-card-title {
         font-family: 'Ubuntu', sans-serif !important;
        font-size: 8px;
        color: #ffffff;
        line-height: 1.3;
    }

    .inbox-card-badge {
        font-size: 7.5px;
        padding: 2px 6px;
        border-radius: 4px;
         font-family: 'Ubuntu', sans-serif !important;
        text-transform: uppercase;
        flex-shrink: 0;
    }

    .inbox-badge-new {
        background: #ef4444;
        color: #ffffff;
    }

    .inbox-badge-read {
        background: rgba(255,255,255,0.15);
        color: rgba(255,255,255,0.6);
    }

    .inbox-card-time {
        font-size: 9px;
        color: rgba(255,255,255,0.35);
    }

    .inbox-card-content {
         font-family: 'Ubuntu', sans-serif !important;
        font-size: 11.5px;
        color: #cbd5e1;
        line-height: 1.4;
        white-space: pre-line;
    }

    .inbox-reward-box {
        background: rgba(245, 158, 11, 0.12);
        border: 1px dashed rgba(245, 158, 11, 0.4);
        border-radius: 8px;
        padding: 8px 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 6px;
        flex-wrap: wrap;
        margin-top: 4px;
        box-sizing: border-box;
    }

    .inbox-reward-text {
         font-family: 'Ubuntu', sans-serif !important;
        font-size: 7.5px;
        color: #fbbf24;
        display: flex;
        align-items: center;
        gap: 4px;
        flex: 1;
        min-width: 100px;
        line-height: 1.4;
        word-break: break-word;
    }

    .inbox-claim-btn {
        background: linear-gradient(180deg, #f59e0b 0%, #d97706 100%);
        border: 1px solid #ffffff;
        border-radius: 6px;
        color: #ffffff;
         font-family: 'Ubuntu', sans-serif !important;
        font-size: 7px;
        padding: 6px 10px;
        cursor: pointer;
        box-shadow: 0 2px 4px rgba(0,0,0,0.3);
        transition: all 0.15s;
        flex-shrink: 0;
        text-align: center;
        white-space: nowrap;
    }

    @media (max-width: 320px) {
        .inbox-reward-box {
            flex-direction: column;
            align-items: stretch;
            gap: 6px;
        }
        .inbox-reward-text {
            justify-content: center;
        }
        .inbox-claim-btn {
            width: 100%;
            padding: 7px 0;
        }
    }

    .inbox-claim-btn:hover {
        background: linear-gradient(180deg, #fbbf24 0%, #f59e0b 100%);
    }

    .inbox-claim-btn.claimed {
        background: #475569;
        border-color: #64748b;
        color: #cbd5e1;
        cursor: default;
        box-shadow: none;
    }

    .inbox-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-top: 1px solid rgba(255,255,255,0.06);
        padding-top: 6px;
        margin-top: 2px;
    }

    .inbox-del-btn {
        background: none;
        border: none;
        color: rgba(239,68,68,0.7);
        font-size: 11px;
        cursor: pointer;
        padding: 2px 4px;
        transition: color 0.15s;
    }

    .inbox-del-btn:hover {
        color: #ef4444;
    }

    /* ========= INBOX TOAST ALERT ========= */
    .inbox-toast-alert {
        position: absolute;
        top: -200px;
        left: 50%;
        transform: translateX(-50%);
        width: 90%;
        max-width: 330px;
        background: rgba(15, 23, 42, 0.95);
        border: 2px solid #38bdf8;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.6), 0 0 15px rgba(56, 189, 248, 0.3);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        padding: 10px 12px;
        box-sizing: border-box;
        z-index: 1020;
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        visibility: hidden;
        opacity: 0;
        pointer-events: none;
        transition: top 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), opacity 0.4s ease, visibility 0.4s;
    }

    .inbox-toast-alert.show {
        top: 60px;
        visibility: visible;
        opacity: 1;
        pointer-events: auto;
    }

    .inbox-toast-icon {
        width: 34px;
        height: 34px;
        border-radius: 8px;
        background: rgba(56, 189, 248, 0.15);
        border: 1px solid rgba(56, 189, 248, 0.4);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #38bdf8;
        font-size: 18px;
        flex-shrink: 0;
    }

    .inbox-toast-body {
        flex: 1;
        overflow: hidden;
    }

    .inbox-toast-title {
         font-family: 'Ubuntu', sans-serif !important;
        font-size: 7px;
        color: #38bdf8;
        text-shadow: 0 0 6px rgba(56, 189, 248, 0.4);
        margin-bottom: 3px;
    }

    .inbox-toast-desc {
         font-family: 'Ubuntu', sans-serif !important;
        font-size: 11px;
        color: #e2e8f0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .inbox-toast-close {
        background: none;
        border: none;
        color: rgba(255, 255, 255, 0.5);
        font-size: 16px;
        cursor: pointer;
        padding: 0 4px;
        line-height: 1;
    }

    .inbox-toast-close:hover { color: #ffffff; }

    /* PWA Install Alert Banner */
    .pwa-install-alert {
        position: absolute;
        top: -250px;
        left: 5%;
        width: 90%;
        background: rgba(15, 23, 42, 0.95);
        border: 3px solid #22c55e;
        border-radius: 16px;
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.75), 0 0 20px rgba(34, 197, 94, 0.25);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        padding: 18px 16px;
        box-sizing: border-box;
        z-index: 1050;
        transition: top 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .pwa-install-alert.show {
        top: 20px;
    }

    .pwa-alert-header {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .pwa-alert-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        border: 2px solid #22c55e;
        object-fit: cover;
    }

    .pwa-alert-title-group {
        display: flex;
        flex-direction: column;
        gap: 4px;
        flex: 1;
    }

    .pwa-alert-title {
         font-family: 'Press Start 2P', monospace;
        font-size: 9px;
        color: #22c55e;
        text-shadow: 0 0 8px rgba(34, 197, 94, 0.4);
        margin: 0;
    }

    .pwa-alert-desc {
         font-family: 'Press Start 2P', monospace;
        font-size: 12px;
        color: rgba(255, 255, 255, 0.8);
        margin: 0;
        line-height: 1.4;
    }

    .pwa-alert-buttons {
        display: flex;
        gap: 12px;
        width: 100%;
    }

    .pwa-btn {
        flex: 1;
         font-family: 'Press Start 2P', monospace;
        font-size: 8px;
        padding: 12px 0;
        border: 3px solid #000000;
        border-radius: 8px;
        cursor: pointer;
        text-align: center;
        transition: all 0.1s;
        box-shadow: 0px 4px 0px #000000;
    }

    .pwa-btn-install {
        background-color: #22c55e;
        color: white;
        text-shadow: 1.5px 1.5px 0px #000000;
    }

    .pwa-btn-install:hover {
        background-color: #4ade80;
    }

    .pwa-btn-install:active {
        transform: translateY(4px);
        box-shadow: 0px 0px 0px #000000;
    }

    .pwa-btn-cancel {
        background-color: #475569;
        color: #cbd5e1;
        text-shadow: 1px 1px 0px #000000;
    }

    .pwa-btn-cancel:hover {
        background-color: #64748b;
    }

    .pwa-btn-cancel:active {
        transform: translateY(4px);
        box-shadow: 0px 0px 0px #000000;
    }

    .pwa-ios-instructions {
         font-family: 'Press Start 2P', monospace;
        font-size: 11px;
        color: rgba(255, 255, 255, 0.9);
        background: rgba(34, 197, 94, 0.1);
        border: 1px dashed rgba(34, 197, 94, 0.4);
        border-radius: 8px;
        padding: 10px;
        margin-top: 2px;
        display: flex;
        align-items: center;
        gap: 8px;
        line-height: 1.4;
    }

    .pwa-ios-icon {
        font-size: 18px;
        display: inline-block;
        flex-shrink: 0;
    }
</style>

<div id="game-ui">
    <!-- Top Bar elements (Profile, Sound, Coins) -->
    <div class="profile-btn" onclick="window.navigateToPage('/profil')">
        @php
        $dbFoto = auth()->user()->foto_profile;
        if (!empty($dbFoto)) {
            if (strpos($dbFoto, 'http://') === 0 || strpos($dbFoto, 'https://') === 0) {
                $profileImgSrc = $dbFoto;
            } elseif (strpos($dbFoto, '/') !== false || strpos($dbFoto, '.gif') !== false) {
                $profileImgSrc = (strpos($dbFoto, '/') === 0) ? $dbFoto : '/' . $dbFoto;
            } else {
                $profileImgSrc = '/game_pacu/assets/image/ui/' . $dbFoto . '.gif';
            }
        } else {
            $profileImgSrc = '/game_pacu/assets/image/ui/profil.gif';
        }
        @endphp
        <img src="{{ $profileImgSrc }}" alt="Profile">
    </div>

    <!-- Sound Toggle (Top Middle) -->
    <div id="sound-btn" class="sound-btn" onclick="openAudioSettings()">
        <img id="sound-icon" src="/game_pacu/assets/image/ui/sound_on.png" alt="Sound">
    </div>

    <!-- Coin Display (Top Right) -->
    <div class="coin-display" onclick="window.navigateToPage('/shop')">
        <span class="sprint-icon me-1"><img src="/game_pacu/assets/image/ui/sprint.png" alt="Sprint" style="width: 20px; height: 20px; object-fit: contain;"></span>
        <span id="header-coin-count">{{ number_format(auth()->user()->kuansing_poin, 0, ',', '.') }}</span>
    </div>
    <div id="ps5-backdrop" class="ps5-backdrop-glow bg-slide-0"></div>

    <div class="menu-main-wrapper">
        <div class="title-banner"></div>

        <!-- Carousel Menu PS5 -->
        <div class="ps5-carousel-container">
            <button class="carousel-nav-btn prev-btn" onclick="prevSlide(event)">
                <img src="/game_pacu/assets/image/ui/btn_kiri.png" alt="Left">
            </button>
            <div class="ps5-carousel-view">
                <div class="ps5-carousel-track" id="carousel-track">
                    <!-- Slide 0: MAIN PACU -->
                    <div class="ps5-card card-green active" data-index="0" onclick="selectSlide(0, event)">
                        <div class="ps5-card-icon">
                            <img src="/game_pacu/assets/image/ui/kayuah.png" alt="Main">
                        </div>
                        <div class="ps5-card-label">MAIN PACU</div>
                        <div class="ps5-pattern">&#9587;</div>
                    </div>
                    <!-- Slide 1: SHOP -->
                    <div class="ps5-card card-purple" data-index="1" onclick="selectSlide(1, event)">
                        <div class="ps5-card-icon">
                            <img src="/game_pacu/assets/image/ui/tentang.png" alt="Shop">
                        </div>
                        <div class="ps5-card-label">SHOP</div>
                        <div class="ps5-pattern">&#9587;</div>
                    </div>
                    <!-- Slide 2: TUKANG JALUAR -->
                    <div class="ps5-card card-orange" data-index="2" onclick="selectSlide(2, event)">
                        <div class="ps5-card-icon">
                            <img src="/game_pacu/assets/image/ui/tukang.png" alt="Tukang">
                        </div>
                        <div class="ps5-card-label">TUKANG JALUAR</div>
                        <div class="ps5-pattern">&#9711;</div>
                    </div>
                    <!-- Slide 3: CARI PEMAIN -->
                    <div class="ps5-card card-red" data-index="3" onclick="selectSlide(3, event)">
                        <div class="ps5-card-icon">
                            <img src="/game_pacu/assets/image/ui/magnifer.png" alt="Search">
                        </div>
                        <div class="ps5-card-label">CARI PEMAIN</div>
                        <div class="ps5-pattern">&#9651;</div>
                    </div>
                    <!-- Slide 4: LEADERBOARD -->
                    <div class="ps5-card card-yellow" data-index="4" onclick="selectSlide(4, event)">
                        <div class="ps5-card-icon">
                            <img src="/game_pacu/assets/image/ui/piala.png" alt="Trophy">
                        </div>
                        <div class="ps5-card-label">LEADERBOARD</div>
                        <div class="ps5-pattern">&#9633;</div>
                    </div>
                </div>
            </div>
            <button class="carousel-nav-btn next-btn" onclick="nextSlide(event)">
                <img src="/game_pacu/assets/image/ui/btn_kanan.png" alt="Right">
            </button>
        </div>

        <!-- Slide Details & Button -->
        <div class="ps5-details-container">
            <div class="ps5-details-title" id="active-title" style="--glow-color: rgba(34, 197, 94, 0.6)">MAIN PACU</div>
            <div class="ps5-details-desc" id="active-desc">Cari lawan & mulai balapan jalur</div>
            <div class="ps5-indicators">
                <span class="ps5-dot active" onclick="jumpToSlide(0)"></span>
                <span class="ps5-dot" onclick="jumpToSlide(1)"></span>
                <span class="ps5-dot" onclick="jumpToSlide(2)"></span>
                <span class="ps5-dot" onclick="jumpToSlide(3)"></span>
                <span class="ps5-dot" onclick="jumpToSlide(4)"></span>
            </div>
        </div>
    </div>

    <div class="loading-overlay" id="loading-overlay">
        <div class="matchmaking-radar-container">
            <div class="radar"></div>
            <div class="magnifying-glass">
                <div class="lens"></div>
                <div class="handle"></div>
            </div>
        </div>
        <div class="loading-text">Mencari<br>Lawan...</div>
        <button class="pixel-btn btn-cancel" onclick="batalCari()">BATAL</button>
    </div>

    <!-- Custom Coming Soon Modal -->
    <div id="coming-soon-modal"
        style="display: none; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.85); z-index: 210; align-items: center; justify-content: center; box-sizing: border-box;">
        <div class="coming-soon-card"
            style="background: #ffffff; border: 4px solid #000000; box-shadow: 6px 6px 0px #000000; border-radius: 12px; width: 85%; max-width: 300px; padding: 22px 18px; text-align: center; box-sizing: border-box;  font-family: 'Press Start 2P', monospace;">
            <div style="font-size: 10px; color: #a855f7; margin-bottom: 20px; border-bottom: 3px dashed #000000; padding-bottom: 12px; font-weight: bold; letter-spacing: 0.5px;"><i class="bi bi-gear-fill me-1"></i> FITUR DUMMY</div>
            <p style=" font-family: 'Press Start 2P', monospace; font-size: 13px; color: #374151; margin-bottom: 20px; line-height: 1.5;">Menu ini adalah simulasi dummy dan akan segera dikembangkan di masa mendatang!</p>
            <button class="pixel-btn" onclick="closeComingSoon()"
                style="margin-top: 0; background-color: #a855f7; border: 3px solid #000000; box-shadow: inset 0 2px 0px rgba(255,255,255,0.4), 0px 4px 0px #000000; color: white; padding: 12px; font-size: 9px; cursor: pointer; text-transform: uppercase; width: 100%; text-shadow: 1.5px 1.5px 0px #000000;">OKE</button>
        </div>
    </div>

    <!-- Custom Audio Settings Modal -->
    <div id="audio-settings-modal"
        style="display: none; position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(2, 30, 20, 0.88); backdrop-filter: blur(4px); z-index: 200; align-items: center; justify-content: center; box-sizing: border-box;">
        <div class="audio-modal-card"
            style="background: #0f1a12; border: 3px solid #22c55e; box-shadow: 0 0 24px rgba(34,197,94,0.3), 6px 6px 0px #000000; border-radius: 12px; width: 85%; max-width: 300px; padding: 22px 18px; text-align: center; box-sizing: border-box;  font-family: 'Press Start 2P', monospace;">
            <!-- Title -->
            <div style="font-size: 10px; color: #34d399; margin-bottom: 18px; border-bottom: 2px dashed #22c55e; padding-bottom: 12px; font-weight: bold; letter-spacing: 0.5px;">
                PENGATURAN SUARA
            </div>
            <!-- BGM Row -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 14px;">
                <span style="font-size: 8px; color: #d1fae5; text-align: left;">MUSIK (BGM)</span>
                <button id="bgm-toggle-btn" onclick="toggleBGMSetting()" style=" font-family: 'Press Start 2P', monospace; font-size: 8px; width: 72px; padding: 8px 0; border: 3px solid #000000; border-radius: 6px; cursor: pointer; text-shadow: 1.5px 1.5px 0px #000000; color: white; transition: background-color 0.1s; box-shadow: 0px 3px 0px #000000;">ON</button>
            </div>
           
            <!-- Close Button -->
            <button onclick="closeAudioSettings()" style=" font-family: 'Press Start 2P', monospace; background-color: #22c55e; border: 3px solid #000000; box-shadow: inset 0 2px 0px rgba(255,255,255,0.3), 0px 4px 0px #000000; color: white; padding: 12px; font-size: 9px; cursor: pointer; text-transform: uppercase; width: 100%; text-shadow: 1.5px 1.5px 0px #000000; border-radius: 6px;">OKE</button>
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
                {{-- <span class="chat-online-dot"></span>
                <span id="chat-online-count">0</span> online --}}
            </div>
            <button onclick="toggleChat()" style="background: none; border: none; color: rgba(255,255,255,0.6); font-size: 14px; font-weight: bold; cursor: pointer; padding: 0 4px; line-height: 1;" title="Tutup"><i class="bi bi-x-lg"></i></button>
        </div>
    </div>
    <div id="chat-messages" class="scrollable">
        <div class="chat-system-msg">” Selamat datang di Global Chat ”</div>
    </div>
    <div class="chat-input-area">
        <input type="text" id="chat-input" placeholder="Ketik pesan..." maxlength="200" onkeydown="if(event.key==='Enter') sendChat()">
        <button id="chat-send-btn" onclick="sendChat()" title="Kirim"><i class="bi bi-send-fill"></i></button>
    </div>
</div>

<!-- ===== INBOX SIDEBAR (RIGHT TOGGLE) ===== -->
<div id="inbox-backdrop" onclick="toggleInbox()"></div>
<div id="inbox-toggle-btn" onclick="toggleInbox()" title="Inbox & Pesan">
    <span class="inbox-icon"><i class="bi bi-envelope-fill text-dark"></i></span>
    <span id="inbox-unread-dot"></span>
</div>

<div id="inbox-sidebar">
    <div class="inbox-header">
        <div class="inbox-header-title">INBOX</div>
        <button onclick="toggleInbox()" style="background: none; border: none; color: rgba(255,255,255,0.6); font-size: 14px; font-weight: bold; cursor: pointer; padding: 0 4px; line-height: 1;" title="Tutup">âœ•</button>
    </div>
    <div id="inbox-messages-list" class="scrollable">
        <div style="text-align: center; color: rgba(255,255,255,0.4); font-size: 11px; padding: 20px 0;">Memuat pesan inbox...</div>
    </div>
</div>

<!-- INBOX TOAST ALERT NOTIFICATION -->
<div id="inbox-toast-alert" class="inbox-toast-alert" onclick="openInboxFromToast()">
    <div class="inbox-toast-icon"><i class="bi bi-envelope-exclamation-fill"></i></div>
    <div class="inbox-toast-body">
        <div class="inbox-toast-title">PESAN BARU DARI ADMIN!</div>
        <div class="inbox-toast-desc" id="inbox-toast-desc">Memuat pesan...</div>
    </div>
    <button class="inbox-toast-close" onclick="event.stopPropagation(); closeInboxToast()">&times;</button>
</div>

<!-- PWA Install Alert Dialog -->
<div id="pwa-install-alert" class="pwa-install-alert">
    <div class="pwa-alert-header">
        <img src="/game_pacu/assets/image/ui/pwa-icon-192.png" alt="Icon Game" class="pwa-alert-icon">
        <div class="pwa-alert-title-group">
            <h4 class="pwa-alert-title"><i class="bi bi-download me-1"></i> PASANG GAME</h4>
            <p class="pwa-alert-desc">Pasang game Pacu Jalur di Home Screen kamu untuk bermain lebih lancar, cepat, dan layar penuh!</p>
        </div>
    </div>
    <div id="pwa-ios-guide" class="pwa-ios-instructions" style="display: none;">
        <span class="pwa-ios-icon"><i class="bi bi-share-fill"></i></span>
        <span>Ketuk tombol <strong>Bagikan (Share)</strong> di Safari lalu pilih <strong>'Tambahkan ke Layar Utama (Add to Home Screen)'</strong>.</span>
    </div>
    <div class="pwa-alert-buttons">
        <button id="pwa-btn-cancel" class="pwa-btn pwa-btn-cancel">BATAL</button>
        <button id="pwa-btn-install" class="pwa-btn pwa-btn-install">PASANG</button>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    var synced = sessionStorage.getItem('synced_this_session');
    var currentVer = localStorage.getItem('app_version');
    var serverVer = "{{ config('app.version', '1.0.5') }}";
    if (!synced || currentVer !== serverVer) {
        window.location.href = "{{ route('loading') }}";
    }
})();
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
            const okBtn = overlay.querySelector('#custom-alert-ok-btn');
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
                        <button class="fullscreen-btn fullscreen-btn-no" id="custom-confirm-no-btn" style="width: 100px;">BATAL</button>
                    </div>
                </div>
            `;
            document.body.appendChild(overlay);
            overlay.offsetHeight;
            overlay.classList.add('show');
            const yesBtn = overlay.querySelector('#custom-confirm-yes-btn');
            const noBtn = overlay.querySelector('#custom-confirm-no-btn');
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

    // Close existing global chat WebSocket connection to prevent duplication
    if (window.chatWs) {
        window.chatWs.onclose = null;
        window.chatWs.close();
        window.chatWs = null;
    }

    // Load and Sync customizations
    (function () {
        const initialCoins = {{ auth()->user()->kuansing_poin }};
        const coinEl = document.getElementById('header-coin-count');
        if (coinEl) {
            coinEl.innerText = initialCoins.toLocaleString('id-ID');
        }

        fetch('/tukang-jaluar/get')
            .then(res => res.json())
            .then(data => {
                if (data.customColors) {
                    for (const key in data.customColors) {
                        localStorage.setItem('custom_' + key, data.customColors[key]);
                    }
                }
                if (data.corak_data_url) {
                    localStorage.setItem('corak_data_url', data.corak_data_url);
                } else {
                    localStorage.removeItem('corak_data_url');
                }
                if (data.lambai_data_url) {
                    localStorage.setItem('lambai_data_url', data.lambai_data_url);
                } else {
                    localStorage.removeItem('lambai_data_url');
                }
                if (data.coins !== undefined) {
                    localStorage.setItem('coins', String(data.coins));
                    if (coinEl) {
                        coinEl.innerText = data.coins.toLocaleString('id-ID');
                    }
                }
            })
            .catch(err => console.error('Failed to sync customizations:', err));
    })();

    // Audio Settings Modal handlers
    window.openAudioSettings = function() {
        const modal = document.getElementById('audio-settings-modal');
        if (modal) {
            modal.style.display = 'flex';
            window.syncAudioModalButtons();
        }
    };

    window.closeAudioSettings = function() {
        const modal = document.getElementById('audio-settings-modal');
        if (modal) modal.style.display = 'none';
    };

    window.syncAudioModalButtons = function() {
        const bgmMuted = localStorage.getItem('bgm_muted') === 'true';
        const sfxMuted = localStorage.getItem('sfx_muted') === 'true';

        const bgmBtn = document.getElementById('bgm-toggle-btn');
        const sfxBtn = document.getElementById('sfx-toggle-btn');

        if (bgmBtn) {
            if (bgmMuted) {
                bgmBtn.textContent = 'OFF';
                bgmBtn.style.backgroundColor = '#ef4444';
                bgmBtn.style.boxShadow = '0px 3px 0px #7f1d1d';
            } else {
                bgmBtn.textContent = 'ON';
                bgmBtn.style.backgroundColor = '#16a34a';
                bgmBtn.style.boxShadow = '0px 3px 0px #14532d';
            }
        }

        if (sfxBtn) {
            if (sfxMuted) {
                sfxBtn.textContent = 'OFF';
                sfxBtn.style.backgroundColor = '#ef4444';
                sfxBtn.style.boxShadow = '0px 3px 0px #7f1d1d';
            } else {
                sfxBtn.textContent = 'ON';
                sfxBtn.style.backgroundColor = '#16a34a';
                sfxBtn.style.boxShadow = '0px 3px 0px #14532d';
            }
        }

        // Update sound icon in topbar
        window.updateSoundIcon();
    };

    // Override updateSoundIcon ” ganti ikon berdasarkan status BGM di localStorage
    window.updateSoundIcon = function() {
        const soundIcon = document.getElementById('sound-icon');
        if (!soundIcon) return;
        const bgmMuted = localStorage.getItem('bgm_muted') === 'true';
        soundIcon.src = bgmMuted
            ? '/game_pacu/assets/image/ui/sound_off.png'
            : '/game_pacu/assets/image/ui/sound_on.png';
    };

    // Sync ikon suara saat halaman pertama dimuat
    window.updateSoundIcon();

    // toggleBGMSetting & toggleSFXSetting sudah didefinisikan global
    // di game-layout.js dan langsung apply ke window.globalBGM

    window.cariLawan = function() {
        document.getElementById('loading-overlay').style.display = 'flex';
        window.searchTimeout = setTimeout(() => {}, 60000);
    };

    window.batalCari = function() {
        document.getElementById('loading-overlay').style.display = 'none';
        if (window.searchTimeout) {
            clearTimeout(window.searchTimeout);
        }
    };





    // Carousel Menu PS5
    const slidesData = [
        { title: 'MAIN PACU', desc: 'Cari lawan & mulai balapan jalur', url: '/room', glow: 'rgba(34, 197, 94, 0.6)', action: 'link' },
        { title: 'SHOP', desc: 'Beli koin KP & unduh template item', url: '/shop', glow: 'rgba(168, 85, 247, 0.6)', action: 'link' },
        { title: 'TUKANG JALUAR', desc: 'Kustomisasi perahu & pendayung', url: '/tukang-jaluar', glow: 'rgba(249, 115, 22, 0.6)', action: 'link' },
        { title: 'CARI PEMAIN', desc: 'Cari profil pemain lain', url: '/cari-pemain', glow: 'rgba(239, 68, 68, 0.6)', action: 'link' },
        { title: 'LEADERBOARD', desc: 'Lihat peringkat pemain terbaik', url: '/leaderboard', glow: 'rgba(234, 179, 8, 0.6)', action: 'link' },
    ];
    let currentSlide = 0;

    window.updateCarousel = function() {
        const track = document.getElementById('carousel-track');
        const view = document.querySelector('.ps5-carousel-view');
        if (!track || !view) return;
        const cards = document.querySelectorAll('.ps5-card');
        const dots = document.querySelectorAll('.ps5-dot');
        const backdrop = document.getElementById('ps5-backdrop');

        const cardWidth = 110;
        const gap = 20;

        const viewWidth = view.offsetWidth || 300;
        const centerOffset = (viewWidth - cardWidth) / 2;

        const translateX = centerOffset - currentSlide * (cardWidth + gap);
        track.style.transform = `translateX(${translateX}px)`;

        cards.forEach((card, idx) => {
            if (idx === currentSlide) {
                card.classList.add('active');
            } else {
                card.classList.remove('active');
            }
        });

        dots.forEach((dot, idx) => {
            if (idx === currentSlide) {
                dot.classList.add('active');
            } else {
                dot.classList.remove('active');
            }
        });

        const activeData = slidesData[currentSlide];
        document.getElementById('active-title').innerText = activeData.title;
        document.getElementById('active-desc').innerText = activeData.desc;

        document.getElementById('active-title').style.setProperty('--glow-color', activeData.glow);
        const actionBtn = document.getElementById('ps5-action-btn');
        if (actionBtn) {
            actionBtn.style.setProperty('--glow-color', activeData.glow);
        }

        if (backdrop) {
            backdrop.className = 'ps5-backdrop-glow bg-slide-' + currentSlide;
        }
    };

    window.nextSlide = function(e) {
        if (e) e.stopPropagation();
        currentSlide = (currentSlide + 1) % slidesData.length;
        updateCarousel();
    };

    window.prevSlide = function(e) {
        if (e) e.stopPropagation();
        currentSlide = (currentSlide - 1 + slidesData.length) % slidesData.length;
        updateCarousel();
    };

    window.selectSlide = function(idx, e) {
        if (e) e.stopPropagation();
        if (idx !== currentSlide) {
            currentSlide = idx;
            updateCarousel();
            return;
        }
        updateCarousel();
        activateActiveSlide();
    };

    window.jumpToSlide = function(idx) {
        if (currentSlide !== idx) {
            currentSlide = idx;
            updateCarousel();
        }
    };

    window.activateActiveSlide = function() {
        const activeData = slidesData[currentSlide];
        if (activeData.action === 'search') {
            cariLawan();
        } else if (activeData.action === 'link_dummy') {
            openComingSoon();
        } else {
            const url = activeData.url;
            window.navigateToPage(url);
        }
    };

    window.openComingSoon = function() {
        const modal = document.getElementById('coming-soon-modal');
        if (modal) modal.style.display = 'flex';
    };

    window.closeComingSoon = function() {
        const modal = document.getElementById('coming-soon-modal');
        if (modal) modal.style.display = 'none';
    };

    // Keyboard Navigation Controller
    document.addEventListener('keydown', function (e) {
        if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
        if (!document.getElementById('carousel-track')) return;
        if (e.key === 'ArrowLeft') {
            prevSlide();
        } else if (e.key === 'ArrowRight') {
            nextSlide();
        } else if (e.key === 'Enter' || e.key === ' ') {
            activateActiveSlide();
        }
    });

    function initMenuSwipeGestures() {
        const container = document.querySelector('.ps5-carousel-container');
        if (!container || container.dataset.swipeBound === '1') return;
        container.dataset.swipeBound = '1';

        let touchStartX = 0;
        let touchEndX = 0;

        container.addEventListener('touchstart', e => {
            touchStartX = e.changedTouches[0].screenX;
        }, { passive: true });

        container.addEventListener('touchend', e => {
            touchEndX = e.changedTouches[0].screenX;
            const diff = touchEndX - touchStartX;
            if (Math.abs(diff) > 40) {
                if (diff < 0) {
                    nextSlide();
                } else {
                    prevSlide();
                }
            }
        }, { passive: true });
    }

    document.addEventListener('game:page-ready', function () {
        if (!document.getElementById('carousel-track')) return;
        setTimeout(updateCarousel, 50);
        setTimeout(updateCarousel, 400);
        initMenuSwipeGestures();
        initGlobalChat();
    });
    setTimeout(updateCarousel, 100);
    initMenuSwipeGestures();

    // Helper escapeHTML
    window.escapeHTML = window.escapeHTML || function(str) {
        if (str === null || str === undefined) return '';
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    };

    // ============= GLOBAL CHAT =============
    const chatCurrentUserId  = {{ auth()->id() }};
    const chatCurrentUser    = "{{ addslashes(auth()->user()->nama_jalur ?? auth()->user()->email) }}";
    let chatOpen             = false;
    let chatUnread           = 0;
    const MAX_MESSAGES       = 80;

    function initGlobalChat() {
        if (window.chatWs) return;

        const protocol = window.location.protocol === 'https:' ? 'wss:' : 'ws:';
        let wsUrl;
        if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1' || window.location.hostname.startsWith('192.168.')) {
            wsUrl = `${protocol}//${window.location.hostname}:8080`;
        } else {
            wsUrl = `${protocol}//${window.location.hostname}/ws`;
        }
        
        window.chatWs = new WebSocket(wsUrl);

        window.chatWs.onopen = () => {
            if (window.chatWs) {
                window.chatWs.send(JSON.stringify({
                    type: 'join',
                    roomId: 'global_chat',
                    payload: {
                        userId: chatCurrentUserId,
                        userName: chatCurrentUser,
                        customizations: {}
                    }
                }));
            }
        };

        window.chatWs.onmessage = (event) => {
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
                } else if (data.type === 'room_update') {
                    const count = data.payload && data.payload.players ? data.payload.players.length : 0;
                    const el = document.getElementById('chat-online-count');
                    if (el) el.textContent = count;
                }
            } catch(e) {}
        };

        window.chatWs.onclose = () => {
            window.chatWs = null;
            // Only reconnect if we are still on the main menu page
            if (document.getElementById('game-ui')) {
                setTimeout(initGlobalChat, 3000);
            }
        };

        window.chatWs.onerror = () => {};
    }

    function appendChatMessage(payload) {
        const container = document.getElementById('chat-messages');
        if (!container) return;

        const isMe = parseInt(payload.userId) === chatCurrentUserId;

        const d = new Date(payload.timestamp);
        const hh = String(d.getHours()).padStart(2,'0');
        const mm = String(d.getMinutes()).padStart(2,'0');
        const isRoomShare = payload.message && payload.message.startsWith('ðŸ” ROOM:');

        const msgEl = document.createElement('div');
        msgEl.className = 'chat-msg' + (isMe ? ' is-me' : '');
        msgEl.innerHTML = `
            <div class="chat-msg-name">${window.escapeHTML(payload.userName)}</div>
            <div class="chat-msg-bubble${isRoomShare ? ' room-share' : ''}">${window.escapeHTML(payload.message)}</div>
            <div class="chat-msg-time">${hh}:${mm}</div>
        `;
        container.appendChild(msgEl);

        while (container.children.length > MAX_MESSAGES) {
            container.removeChild(container.firstChild);
        }

        container.scrollTop = container.scrollHeight;

        if (!chatOpen && !isMe) {
            chatUnread++;
            const dot = document.getElementById('chat-unread-dot');
            if (dot) dot.style.display = 'block';
        }
    }

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
        if (!msg) return;
        if (!window.chatWs || window.chatWs.readyState !== WebSocket.OPEN) {
            return;
        }
        window.chatWs.send(JSON.stringify({
            type: 'global_chat',
            roomId: 'global_chat',
            payload: {
                userId: chatCurrentUserId,
                userName: chatCurrentUser,
                message: msg
            }
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
        if (sidebar) {
            if (chatOpen) {
                sidebar.classList.add('open');
                if (backdrop) backdrop.classList.add('show');
                chatUnread = 0;
                const dot = document.getElementById('chat-unread-dot');
                if (dot) dot.style.display = 'none';
                adaptChatSidebarViewport();
                setTimeout(() => {
                    if (inp) inp.focus();
                }, 300);
                const msgs = document.getElementById('chat-messages');
                if (msgs) msgs.scrollTop = msgs.scrollHeight;
            } else {
                sidebar.classList.remove('open');
                if (backdrop) backdrop.classList.remove('show');
                if (inp) inp.blur();
                if (document.activeElement) document.activeElement.blur();
                if (sidebar) {
                    sidebar.style.height = '100%';
                    sidebar.style.top = '0';
                }
                setTimeout(() => {
                    window.scrollTo({ top: 0, left: 0, behavior: 'instant' });
                    document.body.scrollTop = 0;
                    document.documentElement.scrollTop = 0;
                }, 50);
            }
        }
    };

    // Close chat when clicking outside
    document.addEventListener('click', function(e) {
        if (!chatOpen) return;
        const sidebar = document.getElementById('chat-sidebar');
        const toggleBtn = document.getElementById('chat-toggle-btn');
        const inp = document.getElementById('chat-input');

        if ((sidebar && sidebar.contains(e.target)) || (toggleBtn && toggleBtn.contains(e.target))) {
            return;
        }

        if (inp && document.activeElement === inp) {
            inp.blur();
            return;
        }

        toggleChat();
    });

    // Reset mobile viewport scroll when input loses focus
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

    initGlobalChat();

    // ============= INBOX SYSTEM & TOAST ALERT =============
    let inboxOpen = false;
    let inboxData = [];
    let inboxUnreadCount = 0;
    let inboxToastTimer = null;

    window.fetchGameInbox = function(checkNewToast = true) {
        fetch('/inbox/list')
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    inboxData = data.inboxes || [];
                    inboxUnreadCount = data.unread_count || 0;
                    renderInboxList();
                    updateInboxBadge();

                    if (checkNewToast && inboxUnreadCount > 0) {
                        checkAndShowInboxToast();
                    }
                }
            })
            .catch(err => console.error('[Inbox] Fetch error:', err));
    };

    function updateInboxBadge() {
        const dot = document.getElementById('inbox-unread-dot');
        if (dot) {
            dot.style.display = inboxUnreadCount > 0 ? 'block' : 'none';
        }
    }

    function renderInboxList() {
        const container = document.getElementById('inbox-messages-list');
        if (!container) return;

        if (!inboxData || inboxData.length === 0) {
            container.innerHTML = `
                <div style="text-align: center; color: rgba(255,255,255,0.4); font-size: 11px; padding: 40px 10px;">
                    <i class="bi bi-inbox" style="font-size: 28px; display: block; margin-bottom: 8px; opacity: 0.5;"></i>
                    Belum ada pesan
                </div>
            `;
            return;
        }

        let html = '';
        inboxData.forEach(item => {
            const isUnread = !item.is_read;
            const hasReward = item.reward_coins > 0;

            let typeBadge = '';
            if (item.type === 'reward') {
                typeBadge = '<span style="font-size:7px; background:rgba(245,158,11,0.2); color:#fbbf24; border:1px solid rgba(245,158,11,0.4); border-radius:3px; padding:1px 4px; font-family:\'Press Start 2P\',monospace;">HADIAH</span>';
            } else if (item.type === 'announcement') {
                typeBadge = '<span style="font-size:7px; background:rgba(59,130,246,0.2); color:#60a5fa; border:1px solid rgba(59,130,246,0.4); border-radius:3px; padding:1px 4px; font-family:\'Press Start 2P\',monospace;">PENGUMUMAN</span>';
            } else if (item.type === 'warning') {
                typeBadge = '<span style="font-size:7px; background:rgba(239,68,68,0.2); color:#f87171; border:1px solid rgba(239,68,68,0.4); border-radius:3px; padding:1px 4px; font-family:\'Press Start 2P\',monospace;">PERINGATAN</span>';
            } else {
                typeBadge = '<span style="font-size:7px; background:rgba(148,163,184,0.2); color:#cbd5e1; border:1px solid rgba(148,163,184,0.4); border-radius:3px; padding:1px 4px; font-family:\'Press Start 2P\',monospace;">INFO</span>';
            }

            html += `
                <div class="inbox-card ${isUnread ? 'unread' : ''}" id="inbox-card-${item.id}">
                    <div class="inbox-card-top">
                        <div style="display:flex; align-items:center; gap:5px; flex-wrap:wrap;">
                            ${typeBadge}
                            <div class="inbox-card-title">${escapeHTML(item.title)}</div>
                        </div>
                        <span class="inbox-card-badge ${isUnread ? 'inbox-badge-new' : 'inbox-badge-read'}">
                            ${isUnread ? 'BARU' : 'DIBACA'}
                        </span>
                    </div>
                    <div class="inbox-card-time"><i class="bi bi-clock me-1"></i>${item.created_at_formatted}</div>
                    <div class="inbox-card-content">${escapeHTML(item.content)}</div>
            `;

            if (hasReward) {
                html += `
                    <div class="inbox-reward-box">
                        <span class="inbox-reward-text">
                            <i class="bi bi-coin"></i> +${item.reward_coins.toLocaleString('id-ID')} KP
                        </span>
                        <button class="inbox-claim-btn ${item.is_claimed ? 'claimed' : ''}"
                                id="claim-btn-${item.id}"
                                onclick="claimInboxReward(${item.id}, ${item.reward_coins})"
                                ${item.is_claimed ? 'disabled' : ''}>
                            ${item.is_claimed ? 'SUDAH DIKLAIM' : 'KLAIM KOIN'}
                        </button>
                    </div>
                `;
            }

            html += `
                    <div class="inbox-card-footer">
                        <button class="inbox-del-btn" onclick="deleteInboxMessage(${item.id})" title="Hapus Pesan">
                            <i class="bi bi-trash-fill me-1"></i>Hapus
                        </button>
                    </div>
                </div>
            `;
        });

        container.innerHTML = html;
    }

    function adaptInboxSidebarViewport() {
        const sidebar = document.getElementById('inbox-sidebar');
        if (!sidebar || !inboxOpen) return;
        if (window.visualViewport) {
            sidebar.style.height = window.visualViewport.height + 'px';
            sidebar.style.top = window.visualViewport.offsetTop + 'px';
        }
        window.scrollTo(0, 0);
    }

    if (window.visualViewport) {
        window.visualViewport.addEventListener('resize', () => {
            if (inboxOpen) adaptInboxSidebarViewport();
        });
        window.visualViewport.addEventListener('scroll', () => {
            if (inboxOpen) adaptInboxSidebarViewport();
        });
    }

    window.toggleInbox = function() {
        inboxOpen = !inboxOpen;
        const sidebar = document.getElementById('inbox-sidebar');
        const backdrop = document.getElementById('inbox-backdrop');

        if (inboxOpen && chatOpen) {
            toggleChat();
        }

        if (sidebar) {
            if (inboxOpen) {
                sidebar.classList.add('open');
                if (backdrop) backdrop.classList.add('show');
                adaptInboxSidebarViewport();
                fetchGameInbox(false);
                markAllUnreadAsRead();
            } else {
                sidebar.classList.remove('open');
                if (backdrop) backdrop.classList.remove('show');
                if (sidebar) {
                    sidebar.style.height = '100%';
                    sidebar.style.top = '0';
                }
            }
        }
    };

    function markAllUnreadAsRead() {
        const unreadItems = inboxData.filter(i => !i.is_read);
        if (unreadItems.length === 0) return;

        unreadItems.forEach(item => {
            fetch(`/inbox/${item.id}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            }).catch(e => console.error(e));
            item.is_read = true;
        });

        inboxUnreadCount = 0;
        updateInboxBadge();
        setTimeout(renderInboxList, 300);
    }

    window.claimInboxReward = function(inboxId, coins) {
        const btn = document.getElementById(`claim-btn-${inboxId}`);
        if (btn) btn.disabled = true;

        fetch(`/inbox/${inboxId}/claim`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(async data => {
            if (data.success) {
                const coinEl = document.getElementById('header-coin-count');
                if (coinEl && data.new_coins !== undefined) {
                    coinEl.innerText = data.new_coins.toLocaleString('id-ID');
                    localStorage.setItem('coins', String(data.new_coins));
                }

                if (btn) {
                    btn.classList.add('claimed');
                    btn.textContent = 'SUDAH DIKLAIM';
                }

                const item = inboxData.find(i => i.id === inboxId);
                if (item) {
                    item.is_claimed = true;
                    item.is_read = true;
                }

                await showHTMLAlert(`+${coins.toLocaleString('id-ID')} KOIN KP BERHASIL DIKLAIM!`, "KLAIM HADIAH");
            } else {
                await showHTMLAlert(data.message || 'Gagal mengklaim koin.', "PERINGATAN");
                if (btn) btn.disabled = false;
            }
        })
        .catch(err => {
            console.error('Claim reward error:', err);
            if (btn) btn.disabled = false;
        });
    };

    window.deleteInboxMessage = async function(inboxId) {
        const confirmed = await showHTMLConfirm('Hapus pesan inbox ini?', 'HAPUS PESAN');
        if (!confirmed) return;

        fetch(`/inbox/${inboxId}/delete`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                inboxData = inboxData.filter(i => i.id !== inboxId);
                const card = document.getElementById(`inbox-card-${inboxId}`);
                if (card) card.remove();
                if (inboxData.length === 0) renderInboxList();
            }
        })
        .catch(err => console.error(err));
    };

    function checkAndShowInboxToast() {
        const unreadItems = inboxData.filter(i => !i.is_read);
        if (unreadItems.length === 0) return;

        const latestUnread = unreadItems[0];
        const notifiedIds = JSON.parse(localStorage.getItem('notified_inbox_ids') || '[]');

        if (!notifiedIds.includes(latestUnread.id)) {
            showInboxToastAlert(latestUnread);
            notifiedIds.push(latestUnread.id);
            localStorage.setItem('notified_inbox_ids', JSON.stringify(notifiedIds));
        }
    }

    function showInboxToastAlert(inboxItem) {
        const toast = document.getElementById('inbox-toast-alert');
        const desc = document.getElementById('inbox-toast-desc');
        if (!toast || !desc) return;

        desc.innerText = `${inboxItem.title} ” ${inboxItem.content}`;
        toast.classList.add('show');

        if (inboxToastTimer) clearTimeout(inboxToastTimer);
        inboxToastTimer = setTimeout(() => {
            closeInboxToast();
        }, 7000);
    }

    window.closeInboxToast = function() {
        const toast = document.getElementById('inbox-toast-alert');
        if (toast) toast.classList.remove('show');
        if (inboxToastTimer) clearTimeout(inboxToastTimer);
    };

    window.openInboxFromToast = function() {
        closeInboxToast();
        if (!inboxOpen) {
            toggleInbox();
        }
    };

    // Close inbox when clicking outside
    document.addEventListener('click', function(e) {
        if (!inboxOpen) return;
        const sidebar = document.getElementById('inbox-sidebar');
        const toggleBtn = document.getElementById('inbox-toggle-btn');
        if (sidebar && !sidebar.contains(e.target) && toggleBtn && !toggleBtn.contains(e.target)) {
            toggleInbox();
        }
    });

    // Initial Inbox load & periodic check
    fetchGameInbox(true);
    setInterval(() => {
        if (!inboxOpen) {
            fetchGameInbox(true);
        }
    }, 20000);



    // ---- PWA Service Worker & Install Prompt Logic ----
    let deferredPrompt;
    const pwaAlert = document.getElementById('pwa-install-alert');
    const btnInstall = document.getElementById('pwa-btn-install');
    const btnCancel = document.getElementById('pwa-btn-cancel');
    const iosGuide = document.getElementById('pwa-ios-guide');

    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('/sw.js?v={{ config("app.version") }}')
                .then(reg => console.log('[PWA] Service Worker registered:', reg.scope))
                .catch(err => console.error('[PWA] Service Worker registration failed:', err));
        });
    }

    function isInstalled() {
        return window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone;
    }

    function isIOS() {
        return /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
    }

    function showPwaNotification() {
        const dismissedTime = localStorage.getItem('pwa-prompt-dismissed');
        const now = Date.now();
        if (dismissedTime && (now - parseInt(dismissedTime)) < (24 * 60 * 60 * 1000)) return;
        if (isInstalled()) return;

        setTimeout(() => {
            if (pwaAlert) pwaAlert.classList.add('show');
        }, 1500);
    }

    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;
        showPwaNotification();
    });

    if (btnInstall) {
        btnInstall.addEventListener('click', async () => {
            if (pwaAlert) pwaAlert.classList.remove('show');
            if (deferredPrompt) {
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                deferredPrompt = null;
            } else if (isIOS()) {
                if (pwaAlert) pwaAlert.classList.add('show');
                if (iosGuide) iosGuide.style.display = 'flex';
                btnInstall.style.display = 'none';
                if (btnCancel) btnCancel.textContent = 'OKE';
            }
        });
    }

    if (btnCancel) {
        btnCancel.addEventListener('click', () => {
            if (pwaAlert) pwaAlert.classList.remove('show');
            localStorage.setItem('pwa-prompt-dismissed', Date.now().toString());
        });
    }

    if (isIOS() && !isInstalled()) {
        showPwaNotification();
    }
}
</script>
@endpush

