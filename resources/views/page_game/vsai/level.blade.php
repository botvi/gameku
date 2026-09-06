@extends('layouts.game')

@section('title', 'Pacu Jalur: The Pixel — Pilih Level VS AI')

@push('styles')
<style>
    body {
        margin: 0;
        padding: 0;
        background-color: #060d18;
        color: #e2e8f0;
        font-family: 'Pixelify Sans', monospace;
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
        background: url('{{ asset_v('game_pacu/assets/image/bg/bgmenu.jpg') }}') no-repeat center center;
        background-size: cover;
        z-index: 10;
        overflow: hidden;
    }

    .ps5-backdrop-glow {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 1;
        pointer-events: none;
        background: rgba(6, 13, 24, 0.9);
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
        transition: transform 0.15s ease;
        padding: 0;
        box-shadow: none;
    }

    .back-btn img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        image-rendering: pixelated;
    }

    .back-btn:hover { transform: scale(1.1); }
    .back-btn:active { transform: scale(0.9); }

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
        width: 32px;
        height: 32px;
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

    #coin-count {
        font-family: 'Pixelify Sans', monospace;
        font-size: 13px;
        font-weight: bold;
        color: #000000;
        text-shadow: 1px 1px 0px #ffffff, -1px -1px 0px #ffffff, 1px -1px 0px #ffffff, -1px 1px 0px #ffffff;
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

    .title-banner {
        font-family: 'Press Start 2P', monospace;
        font-size: 12px;
        background: linear-gradient(180deg, #ffffff 0%, #a5f3fc 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-top: 0;
        margin-bottom: 14px;
        text-align: center;
        line-height: 1.4;
        letter-spacing: 2px;
        z-index: 11;
    }

    /* PS5 Carousel Slider */
    .ps5-carousel-container {
        position: relative;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 12px;
        z-index: 12;
    }

    .ps5-carousel-view {
        width: 100%;
        max-width: 300px;
        height: 150px;
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

    .ps5-card {
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
        opacity: 0.45;
        transform: scale(0.85);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
        overflow: hidden;
    }

    .ps5-card.active {
        opacity: 1;
        transform: scale(1.1);
        border-color: #ffd700;
        background: rgba(30, 41, 59, 0.95);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.7), 0 0 15px rgba(251, 191, 36, 0.4);
    }

    .level-chip-badge {
        font-family: 'Press Start 2P', monospace;
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 8px;
    }

    .ps5-card.completed .level-chip-badge { color: #34d399; }
    .ps5-card.unlocked-current .level-chip-badge { color: #fbbf24; }
    .ps5-card.locked .level-chip-badge { color: #64748b; }

    .ps5-card-label {
        font-family: 'Press Start 2P', monospace;
        font-size: 8px;
        text-align: center;
        color: #ffffff;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.8);
        line-height: 1.4;
        font-weight: bold;
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
        transition: transform 0.2s ease;
        padding: 0;
    }

    .carousel-nav-btn img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        image-rendering: pixelated;
    }

    .carousel-nav-btn:hover { transform: translateY(-50%) scale(1.15); }
    .carousel-nav-btn:active { transform: translateY(-50%) scale(0.9); }

    .prev-btn { left: 4px; }
    .next-btn { right: 4px; }

    /* Slide Details & CTA */
    .ps5-details-container {
        width: 90%;
        max-width: 300px;
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
        color: #ffd700;
        margin-bottom: 6px;
        text-shadow: 0 0 10px rgba(251, 191, 36, 0.5);
    }

    .ps5-details-desc {
        font-family: 'Pixelify Sans', monospace;
        font-size: 12px;
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 6px;
        line-height: 1.4;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
    }

    .reward-badge {
        font-family: 'Pixelify Sans', monospace;
        font-size: 13px;
        color: #FFD700;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 12px;
        text-shadow: 1px 1px 0 #000000, -1px -1px 0 #000000;
    }

    .reward-badge img {
        width: 18px;
        height: 18px;
        image-rendering: pixelated;
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
        text-shadow: 0 1px 2px rgba(0,0,0,0.5);
        transition: all 0.12s ease;
        letter-spacing: 0.5px;
    }

    .pixel-btn:hover:not(:disabled) {
        background: linear-gradient(180deg, #4ade80 0%, #22c55e 100%);
        transform: translateY(-1px);
    }

    .pixel-btn:active:not(:disabled) {
        transform: translateY(4px);
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.15), 0 1px 0 #14532d;
    }

    .pixel-btn:disabled {
        background: #475569;
        border-color: #334155;
        box-shadow: 0 4px 0 #1e293b;
        color: #94a3b8;
        cursor: not-allowed;
        opacity: 0.6;
    }
</style>
@endpush

@section('content')
<div id="game-ui">
    <div id="ps5-backdrop" class="ps5-backdrop-glow"></div>

    <!-- Tombol Back ke Room Menu -->
    <button class="back-btn" onclick="window.navigateToPage('/room')">
        <img src="{{ asset_v('game_pacu/assets/image/ui/back.png') }}" alt="Back">
    </button>

    <!-- Wallet Koin -->
    <div class="coin-display" onclick="window.navigateToPage('/shop')">
        <span class="sprint-icon me-1"><img src="{{ asset_v('game_pacu/assets/image/ui/sprint.png') }}" alt="Sprint" style="width: 20px; height: 20px; object-fit: contain;"></span>
        <span id="coin-count">...</span>
    </div>

    <div class="menu-main-wrapper">
        <div class="title-banner">PILIH LEVEL</div>

        <!-- Carousel Menu PS5 -->
        <div class="ps5-carousel-container">
            <button class="carousel-nav-btn prev-btn" onclick="prevSlide(event)">
                <img src="{{ asset_v('game_pacu/assets/image/ui/btn_kiri.png') }}" alt="Left">
            </button>
            <div class="ps5-carousel-view">
                <div class="ps5-carousel-track" id="carousel-track">
                    <!-- Generated dynamically by JS -->
                </div>
            </div>
            <button class="carousel-nav-btn next-btn" onclick="nextSlide(event)">
                <img src="{{ asset_v('game_pacu/assets/image/ui/btn_kanan.png') }}" alt="Right">
            </button>
        </div>

        <!-- Details & Actions -->
        <div class="ps5-details-container">
            <div class="ps5-details-title" id="active-title">LEVEL 1</div>
            <div class="ps5-details-desc" id="active-desc">TERBUKA</div>
            <div class="reward-badge">
                <span class="sprint-icon me-1"><img src="{{ asset_v('game_pacu/assets/image/ui/sprint.png') }}" alt="Sprint" style="width: 18px; height: 18px; object-fit: contain;"></span>
                <span id="active-reward">+5 SPRINT</span>
            </div>

            <button id="ps5-action-btn" class="pixel-btn" onclick="startSelectedLevel()">MAIN BALAPAN</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
{
    // Sync user coins
    const userCoins = {{ auth()->user()->kuansing_poin ?? 0 }};
    localStorage.setItem('coins', userCoins);
    const coinCountEl = document.getElementById('coin-count');
    if (coinCountEl) {
        coinCountEl.innerText = userCoins.toLocaleString('id-ID');
    }

    // Load level progression
    const serverVsAi = {{ $vsaiUnlocked ?? 1 }};
    const localVsAi = parseInt(localStorage.getItem('vsai_unlocked') || '1', 10);
    const vsaiUnlocked = Math.max(serverVsAi, localVsAi);
    localStorage.setItem('vsai_unlocked', String(vsaiUnlocked));
    const totalLevels = 100;
    let currentSlide = vsaiUnlocked - 1; // Center on current unlocked level
    if (currentSlide < 0) currentSlide = 0;
    if (currentSlide >= totalLevels) currentSlide = totalLevels - 1;

    // Render level cards
    const carouselTrack = document.getElementById('carousel-track');
    if (carouselTrack) {
        for (let level = 1; level <= totalLevels; level++) {
            const card = document.createElement('div');
            let statusChar = '<i class="bi bi-lock-fill text-secondary"></i>';
            let cardStateClass = 'locked';

            if (level < vsaiUnlocked) {
                statusChar = '<i class="bi bi-check-circle-fill text-success"></i>';
                cardStateClass = 'completed';
            } else if (level === vsaiUnlocked) {
                statusChar = '<i class="bi bi-play-circle-fill text-warning"></i>';
                cardStateClass = 'unlocked-current';
            }

            card.className = `ps5-card ${cardStateClass}` + (level === (currentSlide + 1) ? ' active' : '');
            card.dataset.index = level - 1;

            card.innerHTML = `
                <div class="level-chip-badge">${statusChar}</div>
                <div class="ps5-card-label">LEVEL ${level}</div>
            `;

            card.onclick = (e) => {
                selectSlide(level - 1, e);
            };

            carouselTrack.appendChild(card);
        }
    }

    function updateCarousel() {
        const track = document.getElementById('carousel-track');
        const view = document.querySelector('.ps5-carousel-view');
        if (!track || !view) return;
        const cards = document.querySelectorAll('.ps5-card');

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

        // Update details
        const activeLevel = currentSlide + 1;
        const activeTitle = document.getElementById('active-title');
        const activeDesc = document.getElementById('active-desc');
        const activeReward = document.getElementById('active-reward');
        const actionBtn = document.getElementById('ps5-action-btn');

        if (activeTitle) activeTitle.innerText = `LEVEL ${activeLevel}`;

        const rewardCoins = activeLevel * 5;
        if (activeReward) activeReward.innerText = `+${rewardCoins} SPRINT`;

        if (actionBtn && activeDesc) {
            if (activeLevel < vsaiUnlocked) {
                activeDesc.innerText = 'SELESAI';
                activeDesc.style.color = '#34d399';
                actionBtn.innerText = 'MAIN LAGI';
                actionBtn.style.background = 'linear-gradient(180deg, #22c55e 0%, #16a34a 100%)';
                actionBtn.style.borderColor = '#ffffff';
                actionBtn.disabled = false;
            } else if (activeLevel === vsaiUnlocked) {
                activeDesc.innerText = 'TERBUKA';
                activeDesc.style.color = '#fde047';
                actionBtn.innerText = 'MAIN BALAPAN';
                actionBtn.style.background = 'linear-gradient(180deg, #eab308 0%, #ca8a04 100%)';
                actionBtn.style.borderColor = '#854d0e';
                actionBtn.disabled = false;
            } else {
                activeDesc.innerText = 'TERKUNCI';
                activeDesc.style.color = '#94a3b8';
                actionBtn.innerText = 'TERKUNCI';
                actionBtn.style.background = '#475569';
                actionBtn.style.borderColor = '#334155';
                actionBtn.disabled = true;
            }
        }
    }

    function nextSlide(e) {
        if (e) e.stopPropagation();
        if (currentSlide < totalLevels - 1) {
            currentSlide++;
            updateCarousel();
        }
    }

    function prevSlide(e) {
        if (e) e.stopPropagation();
        if (currentSlide > 0) {
            currentSlide--;
            updateCarousel();
        }
    }

    function selectSlide(idx, e) {
        if (e) e.stopPropagation();
        if (currentSlide !== idx) {
            currentSlide = idx;
            updateCarousel();
        }
    }

    function startSelectedLevel() {
        const activeLevel = currentSlide + 1;
        if (activeLevel <= vsaiUnlocked) {
            window.navigateToPage(`/vsai/arena?level=${activeLevel}`);
        }
    }

    window.nextSlide = nextSlide;
    window.prevSlide = prevSlide;
    window.selectSlide = selectSlide;
    window.startSelectedLevel = startSelectedLevel;

    // Swipe Gestures Support
    (() => {
        let touchStartX = 0;
        let touchEndX = 0;

        const container = document.querySelector('.ps5-carousel-container');
        if (!container) return;

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
    })();

    // Keyboard Navigation
    const levelKeydownHandler = function (e) {
        if (e.key === 'ArrowLeft') {
            prevSlide();
        } else if (e.key === 'ArrowRight') {
            nextSlide();
        } else if (e.key === 'Enter') {
            startSelectedLevel();
        }
    };
    document.addEventListener('keydown', levelKeydownHandler);

    // Initial positioning
    setTimeout(updateCarousel, 100);

    // Cleanup when leaving page via Livewire
    document.addEventListener('livewire:navigating', function cleanup() {
        document.removeEventListener('keydown', levelKeydownHandler);
        delete window.nextSlide;
        delete window.prevSlide;
        delete window.selectSlide;
        delete window.startSelectedLevel;
        document.removeEventListener('livewire:navigating', cleanup);
    }, { once: true });
}
</script>
@endpush
