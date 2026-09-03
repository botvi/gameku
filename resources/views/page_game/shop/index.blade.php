@extends('layouts.game')

@section('title', 'Franchise Game — Shop')

@section('content')
<style>
    #shop-dashboard {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        background: #0c111d url('/game_pacu/assets/image/bg/bgmenu.jpg') no-repeat center center;
        background-size: cover;
        z-index: 10;
        box-sizing: border-box;
        overflow: hidden;
    }

    #shop-dashboard .top-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        padding: 14px 16px 8px;
        z-index: 15;
        box-sizing: border-box;
    }

    #shop-dashboard .back-btn-container {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: transform 0.15s ease;
    }

    #shop-dashboard .back-btn-container:hover {
        transform: scale(1.1);
    }

    #shop-dashboard .back-btn-container:active {
        transform: scale(0.9);
    }

    #shop-dashboard .back-btn-container img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        image-rendering: pixelated;
    }

    #shop-dashboard .page-title {
        font-family: 'Press Start 2P', monospace;
        font-size: 13px;
        color: #22c55e;
        text-shadow: 0 0 10px rgba(34, 197, 94, 0.6), 2px 2px 0px #064e3b;
        letter-spacing: 1px;
        margin: 0;
    }

    #shop-dashboard .coin-display {
        display: flex;
        align-items: center;
        gap: 6px;
        z-index: 15;
    }

    #shop-dashboard .coin-icon-wrapper {
        position: relative;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #shop-dashboard .coin-icon-wrapper img {
        width: 100%;
        height: 100%;
        image-rendering: pixelated;
    }

    #shop-dashboard .coin-amount {
        font-family: 'Pixelify Sans', monospace;
        font-size: 13px;
        font-weight: bold;
        color: #000000;
        line-height: 1;
        text-shadow: 1px 1px 0px #ffffff, -1px -1px 0px #ffffff,
                     1px -1px 0px #ffffff, -1px 1px 0px #ffffff;
    }

    #shop-dashboard .banner-container {
        margin: 4px 16px 10px;
        padding: 10px 14px;
        background: rgba(22, 163, 74, 0.25);
        border: 2px solid #22c55e;
        border-radius: 14px;
        text-align: center;
        box-sizing: border-box;
        z-index: 12;
    }

    #shop-dashboard .banner-title {
        font-family: 'Press Start 2P', monospace;
        font-size: 10px;
        font-weight: bold;
        color: #22c55e;
        text-shadow: 0 0 8px rgba(34, 197, 94, 0.6);
        margin-bottom: 4px;
    }

    #shop-dashboard .banner-desc {
        font-family: 'Pixelify Sans', monospace;
        font-size: 11px;
        color: #ffffff;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.8);
        line-height: 1.3;
    }

    #shop-dashboard .tab-bar-container {
        display: flex;
        justify-content: center;
        margin: 0 16px 12px;
        background: rgba(15, 23, 42, 0.85);
        border: 2px solid rgba(34, 197, 94, 0.4);
        border-radius: 12px;
        padding: 4px;
        gap: 6px;
        z-index: 12;
        box-sizing: border-box;
    }

    #shop-dashboard .tab-btn {
        flex: 1;
        padding: 8px 0;
        font-family: 'Press Start 2P', monospace;
        font-size: 9px;
        color: #94a3b8;
        background: transparent;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-align: center;
        user-select: none;
    }

    #shop-dashboard .tab-btn.active {
        color: #ffffff;
        background: #22c55e;
        box-shadow: 0 2px 8px rgba(34, 197, 94, 0.5);
    }

    #shop-dashboard .shop-content-scroll {
        flex: 1;
        overflow-y: auto;
        -webkit-overflow-scrolling: touch;
        padding: 0 16px 30px;
        box-sizing: border-box;
        z-index: 12;
    }

    #shop-dashboard .shop-content-scroll::-webkit-scrollbar {
        width: 6px;
    }

    #shop-dashboard .shop-content-scroll::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.2);
        border-radius: 10px;
    }

    #shop-dashboard .shop-content-scroll::-webkit-scrollbar-thumb {
        background: rgba(34, 197, 94, 0.6);
        border-radius: 10px;
    }

    #shop-dashboard .cards-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(135px, 1fr));
        gap: 12px;
        width: 100%;
        max-width: 480px;
        margin: 0 auto;
        box-sizing: border-box;
    }

    #shop-dashboard .topup-card {
        background: rgba(15, 23, 42, 0.88);
        border: 2px solid #86efac;
        border-radius: 16px;
        padding: 12px 8px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-between;
        text-align: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4);
        transition: transform 0.2s ease, border-color 0.2s ease;
        box-sizing: border-box;
    }

    #shop-dashboard .topup-card:hover {
        transform: translateY(-2px);
        border-color: #22c55e;
        background: rgba(30, 41, 59, 0.95);
    }

    #shop-dashboard .topup-coin-icon {
        width: 36px;
        height: 36px;
        margin-bottom: 4px;
        image-rendering: pixelated;
    }

    #shop-dashboard .topup-amount {
        font-family: 'Pixelify Sans', monospace;
        font-size: 16px;
        font-weight: bold;
        color: #f59e0b;
        text-shadow: 0 1px 0px #78350f;
        margin-bottom: 2px;
    }

    #shop-dashboard .topup-price {
        font-family: 'Pixelify Sans', monospace;
        font-size: 11px;
        font-weight: bold;
        color: #ffffff;
        margin-bottom: 8px;
    }

    #shop-dashboard .btn-action-green {
        width: 100%;
        padding: 7px 0;
        background: #22c55e;
        border: 2px solid #16a34a;
        border-radius: 8px;
        color: #ffffff;
        font-family: 'Press Start 2P', monospace;
        font-size: 8px;
        cursor: pointer;
        text-shadow: 0 1px 2px rgba(0,0,0,0.5);
        box-shadow: 0 3px 0 #14532d;
        transition: all 0.1s ease;
        box-sizing: border-box;
    }

    #shop-dashboard .btn-action-green:hover {
        background: #4ade80;
    }

    #shop-dashboard .btn-action-green:active {
        transform: translateY(2px);
        box-shadow: 0 1px 0 #14532d;
    }

    /* Item Card */
    .item-card {
        background: #ffffff;
        border: 3px solid #93c5fd;
        border-radius: 16px;
        padding: 10px 8px;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-between;
        text-align: center;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        transition: transform 0.2s ease, border-color 0.2s ease;
        box-sizing: border-box;
    }

    .item-card:hover {
        transform: translateY(-2px);
        border-color: #3b82f6;
        background: #f0f9ff;
    }

    .item-img-container {
        width: 60px;
        height: 60px;
        background: #eff6ff;
        border: 2px solid #93c5fd;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 6px;
        overflow: hidden;
    }

    .item-img-container img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        image-rendering: pixelated;
    }

    .item-title {
        font-family: 'Press Start 2P', monospace;
        font-size: 8px;
        font-weight: bold;
        color: #1e3a8a;
        margin-bottom: 4px;
        line-height: 1.2;
    }

    .item-desc {
        font-family: 'Pixelify Sans', monospace;
        font-size: 10px;
        color: #64748b;
        margin-bottom: 8px;
        line-height: 1.2;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .btn-action-blue {
        width: 100%;
        padding: 7px 0;
        background: #0ea5e9;
        border: 2px solid #0284c7;
        border-radius: 8px;
        color: #ffffff;
        font-family: 'Press Start 2P', monospace;
        font-size: 7px;
        cursor: pointer;
        text-shadow: 0 1px 2px #0369a1;
        box-shadow: 0 3px 0 #0369a1;
        transition: all 0.1s ease;
        box-sizing: border-box;
    }

    .btn-action-blue:hover {
        background: #38bdf8;
    }

    .btn-action-blue:active {
        transform: translateY(2px);
        box-shadow: 0 1px 0 #0369a1;
    }

    /* Modal Confirmation Dialog */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.8);
        z-index: 100;
        align-items: center;
        justify-content: center;
        box-sizing: border-box;
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal-card {
        background: #ffffff;
        border: 4px solid #22c55e;
        border-radius: 16px;
        width: 85%;
        max-width: 320px;
        padding: 20px 16px;
        text-align: center;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.6);
        box-sizing: border-box;
        animation: modalBounce 0.25s ease-out;
    }

    @keyframes modalBounce {
        0% { transform: scale(0.8); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }

    .modal-text {
        font-family: 'Pixelify Sans', monospace;
        font-size: 14px;
        font-weight: bold;
        color: #ffffff;
        margin-bottom: 16px;
        line-height: 1.4;
    }

    .modal-buttons {
        display: flex;
        gap: 12px;
        justify-content: center;
    }

    .modal-btn-yes {
        flex: 1;
        padding: 10px 0;
        background: #22c55e;
        border: 2px solid #16a34a;
        border-radius: 8px;
        color: white;
        font-family: 'Press Start 2P', monospace;
        font-size: 9px;
        cursor: pointer;
        box-shadow: 0 3px 0 #ffffff;
    }

    .modal-btn-no {
        flex: 1;
        padding: 10px 0;
        background: #ef4444;
        border: 2px solid #dc2626;
        border-radius: 8px;
        color: white;
        font-family: 'Press Start 2P', monospace;
        font-size: 9px;
        cursor: pointer;
        box-shadow: 0 3px 0 #991b1b;
    }

    /* Toast Notification */
    .toast-notice {
        display: none;
        position: fixed;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        background: #16a34a;
        border: 2px solid #ffffff;
        border-radius: 10px;
        padding: 10px 16px;
        color: #ffffff;
        font-family: 'Press Start 2P', monospace;
        font-size: 9px;
        z-index: 200;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
        text-align: center;
    }

    .toast-notice.show {
        display: block;
        animation: toastFade 0.3s ease-out;
    }

    @keyframes toastFade {
        0% { transform: translate(-50%, 20px); opacity: 0; }
        100% { transform: translate(-50%, 0); opacity: 1; }
    }

    .empty-state {
        grid-column: 1 / -1;
        text-align: center;
        padding: 30px 10px;
        color: #94a3b8;
        font-family: 'Pixelify Sans', monospace;
        font-size: 14px;
    }
</style>

<div id="shop-dashboard">
    <!-- Top Bar -->
    <div>
        <div class="top-bar">
            <div class="back-btn-container" onclick="window.navigateToPage('/main-menu')">
                <img src="/game_pacu/assets/image/ui/back.png" alt="Back" class="back-btn">
            </div>
            <div class="coin-display">
                <span class="sprint-icon me-1"><img src="/game_pacu/assets/image/ui/sprint.png" alt="Sprint" style="width: 20px; height: 20px; object-fit: contain;"></span>
                <span id="shop-coin-count" class="coin-amount">{{ number_format(auth()->user()->kuansing_poin, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Explanation Banner -->
    <div class="banner-container">
        <div class="banner-desc" id="banner-desc">
            Sprint adalah mata uang utama game ini. Kamu bisa mendapatkan Sprint secara gratis saat bermain, atau topup langsung melalui paket di bawah ini!
        </div>
    </div>

    <!-- Tab Switcher Bar -->
    <div class="tab-bar-container">
        <button id="tab-koin-btn" class="tab-btn active" onclick="switchShopTab(0)">BELI SPRINT</button>
        <button id="tab-item-btn" class="tab-btn" onclick="switchShopTab(1)">BELI ITEM</button>
    </div>

    <!-- Scrollable Shop Items Content -->
    <div class="shop-content-scroll">
        <!-- TAB 1: BELI KOIN -->
        <div id="tab-koin-content">
            <div class="cards-grid">
                @forelse($packages as $pkg)
                @php
                    $scale = 1.0;
                    if ($pkg->coin_amount >= 5000) $scale = 1.4;
                    elseif ($pkg->coin_amount >= 2000) $scale = 1.25;
                    elseif ($pkg->coin_amount >= 500) $scale = 1.1;
                @endphp
                <div class="topup-card">
                    <div style="display:flex; justify-content:center; align-items:center; margin: 10px 0;">
                        <img src="/game_pacu/assets/image/ui/sprint.png" alt="Sprint" style="width: {{ 32 * $scale }}px; height: {{ 32 * $scale }}px; object-fit: contain;">
                    </div>
                    <div class="topup-amount">+{{ number_format($pkg->coin_amount, 0, ',', '.') }} Sprint</div>
                    <div class="topup-price">Rp {{ number_format($pkg->price, 0, ',', '.') }}</div>
                    <button class="btn-action-green" onclick="promptTopup({{ $pkg->id }}, {{ $pkg->coin_amount }}, 'Rp {{ number_format($pkg->price, 0, ',', '.') }}')">BELI</button>
                </div>
                @empty
                <div class="empty-state">Belum ada paket topup yang tersedia.</div>
                @endforelse
            </div>
        </div>

        <!-- TAB 2: BELI ITEM -->
        <div id="tab-item-content" style="display: none;">
            <div class="cards-grid">
                @forelse($shopItems as $item)
                @php
                    $isOwned = in_array($item->id, $purchasedIds ?? []);
                @endphp
                <div class="item-card" id="item-card-{{ $item->id }}">
                    <div class="item-img-container">
                        @if(!empty($item->image_path))
                            <img src="{{ asset($item->image_path) }}" alt="{{ $item->name }}">
                        @else
                            <i class="bi bi-file-earmark-image text-info" style="font-size: 28px;"></i>
                        @endif
                    </div>
                    <div class="item-title">{{ $item->name }}</div>
                    <div class="item-desc">{{ $item->description ?? 'Template kustomisasi perahu.' }}</div>
                    @if($isOwned)
                        <button class="btn-action-blue" id="item-btn-{{ $item->id }}" onclick="downloadItem({{ $item->id }}, '{{ addslashes($item->filename) }}')">DOWNLOAD</button>
                    @else
                        <button class="btn-action-green" id="item-btn-{{ $item->id }}" onclick="promptBuyItem({{ $item->id }}, '{{ addslashes($item->name) }}', {{ $item->price_kp }}, '{{ addslashes($item->filename) }}')"><i class="bi bi-lock-fill me-1"></i> {{ number_format($item->price_kp, 0, ',', '.') }} Sprint</button>
                    @endif
                </div>
                @empty
                <div class="empty-state">Belum ada item di shop saat ini.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Modal Dialog Confirmation -->
<div class="modal-overlay" id="confirm-modal">
    <div class="modal-card">
        <div class="modal-text" id="modal-msg">Konfirmasi tindakan?</div>
        <div class="modal-buttons">
            <button class="modal-btn-no" onclick="closeConfirmModal()">BATAL</button>
            <button class="modal-btn-yes" id="modal-btn-confirm">YA</button>
        </div>
    </div>
</div>

<!-- Toast Notice -->
<div class="toast-notice" id="toast-notice">TINDAKAN BERHASIL</div>
@endsection

@push('scripts')
<script>
{
    let currentCoinCount = {{ auth()->user()->kuansing_poin }};
    const purchasedItemIds = new Set({!! json_encode($purchasedIds ?? []) !!});
    let activeTab = 0;

    // Update Coin Display
    function updateCoinDisplay(newCoins) {
        currentCoinCount = newCoins;
        const el = document.getElementById('shop-coin-count');
        if (el) {
            el.innerText = newCoins.toLocaleString('id-ID');
        }
        localStorage.setItem('coins', String(newCoins));
    }

    // Tab Switcher Logic
    window.switchShopTab = function(tabIndex) {
        if (activeTab === tabIndex) return;
        activeTab = tabIndex;

        const btnKoin = document.getElementById('tab-koin-btn');
        const btnItem = document.getElementById('tab-item-btn');
        const contentKoin = document.getElementById('tab-koin-content');
        const contentItem = document.getElementById('tab-item-content');
        const bannerDesc = document.getElementById('banner-desc');

        if (tabIndex === 0) {
            if (btnKoin) btnKoin.classList.add('active');
            if (btnItem) btnItem.classList.remove('active');
            if (contentKoin) contentKoin.style.display = 'block';
            if (contentItem) contentItem.style.display = 'none';

            if (bannerDesc) {
                bannerDesc.innerText = 'Sprint adalah mata uang utama game ini. Kamu bisa mendapatkan Sprint secara gratis saat bermain, atau topup langsung melalui paket di bawah ini!';
            }
        } else {
            if (btnItem) btnItem.classList.add('active');
            if (btnKoin) btnKoin.classList.remove('active');
            if (contentItem) contentItem.style.display = 'block';
            if (contentKoin) contentKoin.style.display = 'none';

            if (bannerDesc) {
                bannerDesc.innerText = 'Gunakan Sprint milikmu untuk membeli dan mengunduh berbagai template kustomisasi premium agar tampilan perahumu semakin keren di arena pacu!';
            }
        }
    };

    // Modal Helpers
    let confirmCallback = null;
    function openConfirmModal(msg, onConfirm) {
        document.getElementById('modal-msg').innerText = msg;
        confirmCallback = onConfirm;
        document.getElementById('confirm-modal').classList.add('active');
    }

    window.closeConfirmModal = function() {
        document.getElementById('confirm-modal').classList.remove('active');
        confirmCallback = null;
    };

    document.addEventListener('click', function (e) {
        if (!e.target.closest('#modal-btn-confirm')) return;
        if (confirmCallback) {
            const cb = confirmCallback;
            closeConfirmModal();
            cb();
        }
    });

    // Toast Notice Helper
    function showToast(text) {
        const toast = document.getElementById('toast-notice');
        if (!toast) return;
        toast.innerText = text;
        toast.classList.add('show');
        setTimeout(() => {
            toast.classList.remove('show');
        }, 2200);
    }

    // Topup Logic
    window.promptTopup = function(packageId, kpAmount, priceString) {
        openConfirmModal(`Apakah Anda ingin membeli +${kpAmount.toLocaleString('id-ID')} KP seharga ${priceString}?`, () => {
            fetch('/topup/create', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ package_id: packageId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    let payBtn = document.getElementById('btnPay');
                    if (!payBtn) {
                        payBtn = document.createElement('button');
                        payBtn.id = 'btnPay';
                        payBtn.style.display = 'none';
                        document.body.appendChild(payBtn);
                    }
                    payBtn.setAttribute('data-signature', data.signature);

                    let snapScript = document.getElementById('klikqris-snap-script');
                    if (snapScript) snapScript.remove();

                    snapScript = document.createElement('script');
                    snapScript.id = 'klikqris-snap-script';
                    snapScript.src = "https://klikqris.com/js/payment-snap.js?t=" + new Date().getTime();
                    document.body.appendChild(snapScript);

                    snapScript.onload = () => {
                        setTimeout(() => payBtn.click(), 300);
                    };

                    startPollingStatus(data.order_id, kpAmount);
                } else {
                    alert(data.message || 'Gagal memulai transaksi topup.');
                }
            })
            .catch(err => {
                console.error('Error topup init:', err);
                alert('Gagal menghubungi gateway pembayaran.');
            });
        });
    };

    function startPollingStatus(orderId, kpAmount) {
        let pollInterval = setInterval(() => {
            fetch(`/topup/status/${orderId}`)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    if (data.status === 'SUCCESS') {
                        clearInterval(pollInterval);
                        updateCoinDisplay(data.coins);
                        showToast(`TOPUP BERHASIL! +${kpAmount.toLocaleString('id-ID')} KP`);
                    } else if (data.status === 'EXPIRED') {
                        clearInterval(pollInterval);
                        alert('Waktu pembayaran QRIS telah habis (Expired).');
                    }
                }
            })
            .catch(err => console.error('Polling status error:', err));
        }, 3000);
    }

    // Download Item Logic
    window.downloadItem = function(itemId, filename) {
        const link = document.createElement('a');
        link.href = `/shop/download-item/${itemId}`;
        link.download = filename;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    };

    // Buy Item Logic
    window.promptBuyItem = function(itemId, itemName, priceKP, filename) {

        if (purchasedItemIds.has(Number(itemId))) {
            downloadItem(itemId, filename);
            return;
        }

        openConfirmModal(`Beli ${itemName} seharga ${priceKP.toLocaleString('id-ID')} KP?`, () => {
            if (currentCoinCount < priceKP) {
                openConfirmModal("KP tidak cukup! Ingin top up koin?", () => {
                    switchShopTab(0);
                });
                return;
            }

            fetch('/shop/buy-item', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ item_id: itemId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    updateCoinDisplay(data.kuansing_poin);
                    purchasedItemIds.add(Number(itemId));

                    // Update button UI
                    const btn = document.getElementById(`item-btn-${itemId}`);
                    if (btn) {
                        btn.className = 'btn-action-blue';
                        btn.innerText = 'DOWNLOAD';
                        btn.onclick = () => downloadItem(itemId, filename);
                    }

                    showToast('PEMBELIAN BERHASIL!');

                    // Trigger immediate download
                    setTimeout(() => {
                        downloadItem(itemId, filename);
                    }, 500);
                } else {
                    alert(data.message || 'Gagal membeli item.');
                }
            })
            .catch(err => {
                console.error('Error buying item:', err);
                alert('Terjadi kesalahan saat membeli item.');
            });
        });
    };

    function syncShopCoins() {
        fetch('/tukang-jaluar/get')
        .then(res => res.json())
        .then(data => {
            if (data.coins !== undefined) {
                updateCoinDisplay(data.coins);
            }
        })
        .catch(err => console.error('Failed to sync coins:', err));
    }

    syncShopCoins();
    document.addEventListener('game:page-ready', function (e) {
        if (!document.getElementById('shop-dashboard')) return;
        syncShopCoins();
    });
}
</script>
@endpush
