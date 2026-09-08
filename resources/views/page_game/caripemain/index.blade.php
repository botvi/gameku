@extends('layouts.game')

@section('title', 'Pacu Jalur: The Pixel ” Cari Pemain')

@section('content')
<style>
    #search-dashboard {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        background: #0c111d url('{{ asset_v('game_pacu/assets/image/bg/bgmenu.jpg') }}') no-repeat center center;
        background-size: cover;
        z-index: 10;
        box-sizing: border-box;
        overflow: hidden;
        padding-bottom: 10px;
    }

    #search-dashboard #ps5-particles {
        display: none;
    }

    #search-dashboard .top-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        width: 100%;
        padding: 16px 20px 8px;
        z-index: 11;
        margin-top: 10px;
        box-sizing: border-box;
    }

    #search-dashboard .back-btn-container {
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: filter 0.15s ease, opacity 0.15s ease;
    }

    #search-dashboard .back-btn {
        width: 36px;
        height: 36px;
        pointer-events: none;
    }

    #search-dashboard .back-btn-container:hover {
        filter: brightness(1.3);
    }

    #search-dashboard .back-btn-container:active {
        filter: brightness(0.8);
        opacity: 0.8;
    }

    #search-dashboard .coin-display {
        display: flex;
        align-items: center;
        gap: 6px;
        z-index: 15;
        box-sizing: border-box;
    }

    #search-dashboard .coin-icon-wrapper {
        position: relative;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #search-dashboard .coin-icon-wrapper img {
        width: 100%;
        height: 100%;
        image-rendering: pixelated;
    }

    #search-dashboard .coin-amount {
        font-family: 'Press Start 2P', monospace;
        font-size: 8px;
        font-weight: bold;
        color: #000000;
        line-height: 1;
        text-shadow:
            1px 1px 0px #ffffff,
            -1px -1px 0px #ffffff,
            1px -1px 0px #ffffff,
            -1px 1px 0px #ffffff;
    }

    #search-dashboard .search-container {
        flex: 1;
        min-height: 0;
        display: flex;
        flex-direction: column;
        width: 100%;
        overflow-y: auto;
        overflow-x: hidden;
        -webkit-overflow-scrolling: touch;
        touch-action: pan-y;
        overscroll-behavior: contain;
        z-index: 11;
        scrollbar-width: thin;
        scrollbar-color: rgba(239, 68, 68, 0.4) rgba(255, 255, 255, 0.02);
        box-sizing: border-box;
        padding: 0 14px 20px;
    }

    #search-dashboard .search-container::-webkit-scrollbar {
        width: 5px;
    }

    #search-dashboard .search-container::-webkit-scrollbar-thumb {
        background: rgba(239, 68, 68, 0.4);
        border-radius: 4px;
    }

    #search-dashboard .search-form {
        width: 100%;
        margin-bottom: 14px;
    }

    #search-dashboard .search-input-wrapper {
        width: 100%;
        display: flex;
        align-items: center;
        background: rgba(22, 28, 45, 0.9);
        border: 1.5px solid rgba(239, 68, 68, 0.4);
        border-radius: 9999px;
        padding: 0 16px;
        height: 46px;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.5), inset 0 1px 0 rgba(255, 255, 255, 0.08);
        transition: all 0.2s ease;
        position: relative;
        box-sizing: border-box;
    }

    #search-dashboard .search-input-wrapper:focus-within {
        border-color: #ef4444;
        box-shadow: 0 0 14px rgba(239, 68, 68, 0.5), 0 4px 16px rgba(0, 0, 0, 0.6);
        background: rgba(15, 20, 35, 0.95);
    }

    #search-dashboard .search-icon {
        color: #f87171;
        font-size: 16px;
        margin-right: 10px;
        flex-shrink: 0;
        pointer-events: none;
    }

    #search-dashboard .search-input {
        flex: 1;
        background: transparent;
        border: none;
        outline: none;
        font-family: 'Press Start 2P', monospace;
        font-size: 10px;
        color: #f87171;
        letter-spacing: 0.5px;
        padding: 0;
        height: 100%;
        text-transform: uppercase;
        user-select: text !important;
        -webkit-user-select: text !important;
        touch-action: auto !important;
        pointer-events: auto !important;
    }

    #search-dashboard .search-input::placeholder {
        color: #f87171;
        opacity: 0.85;
        font-family: 'Press Start 2P', monospace;
        font-size: 10px;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    #search-dashboard .search-clear-btn {
        background: none;
        border: none;
        color: rgba(255, 255, 255, 0.5);
        font-size: 16px;
        padding: 0;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color 0.15s ease;
        margin-left: 8px;
        flex-shrink: 0;
    }

    #search-dashboard .search-clear-btn:hover {
        color: #ef4444;
    }

    #search-dashboard .section-header {
        font-family: 'Press Start 2P', monospace;
        font-size: 8px;
        color: #ffffff;
        letter-spacing: 0.5px;
        margin-bottom: 10px;
        text-shadow: 2px 2px 0px #000000;
        text-transform: uppercase;
    }

    #search-dashboard .players-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-bottom: 20px;
    }

    #search-dashboard .player-row {
        background: rgba(15, 23, 42, 0.88);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 16px;
        padding: 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.2s ease;
        box-shadow: 0 4px 12px rgba(0,0,0,0.4), inset 0 1px 0 rgba(255,255,255,0.06);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
    }

    #search-dashboard .player-row:hover {
        border-color: rgba(239, 68, 68, 0.5);
        background: rgba(30, 41, 59, 0.95);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.5), 0 0 16px rgba(239, 68, 68, 0.2);
    }

    #search-dashboard .player-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    #search-dashboard .player-avatar-wrapper {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        border: 2px solid #ef4444;
        background: #0f172a;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 0 10px rgba(239, 68, 68, 0.3);
    }

    #search-dashboard .player-avatar-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    #search-dashboard .player-details {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    #search-dashboard .player-name {
        font-family: 'Press Start 2P', monospace;
        font-size: 8px;
        color: #ffffff;
        text-shadow: 0 1px 2px rgba(0,0,0,0.5);
    }

    #search-dashboard .player-wins {
        font-size: 11px;
        color: #f59e0b;
        font-weight: bold;
    }

    #search-dashboard .detail-btn {
        background: rgba(239, 68, 68, 0.12);
        border: 1px solid rgba(239, 68, 68, 0.35);
        border-radius: 10px;
        padding: 8px 12px;
        font-family: 'Press Start 2P', monospace;
        font-size: 7px;
        color: #f87171;
        cursor: pointer;
        transition: all 0.2s ease;
        text-shadow: 0 1px 2px rgba(0,0,0,0.5);
    }

    #search-dashboard .detail-btn:hover {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        border-color: #ef4444;
        color: #ffffff;
        box-shadow:
            0 4px 0 #7f1d1d,
            0 6px 16px rgba(239, 68, 68, 0.35);
        transform: translateY(-2px);
        text-shadow: 0 1px 2px rgba(0,0,0,0.4);
    }

    #search-dashboard .detail-btn:active {
        transform: translateY(2px);
        box-shadow: 0 1px 0 #7f1d1d;
    }

    #search-dashboard .players-empty {
        background: rgba(15, 23, 42, 0.85);
        border: 1.5px dashed rgba(255, 255, 255, 0.15);
        border-radius: 16px;
        padding: 24px;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    #search-dashboard .empty-icon {
        font-size: 24px;
        margin-bottom: 8px;
    }

    #search-dashboard .empty-title {
        font-family: 'Press Start 2P', monospace;
        font-size: 8px;
        color: #94a3b8;
        margin-bottom: 4px;
    }

    #search-dashboard .empty-subtitle {
        font-size: 11px;
        color: #64748b;
    }
</style>

@php
$user = auth()->user();
@endphp

<div id="search-dashboard">
    <!-- Top Bar -->
    <div>
        <div class="top-bar">
            <div class="back-btn-container" onclick="goBack()">
                <img class="back-btn" src="{{ asset_v('game_pacu/assets/image/ui/back.png') }}" alt="Kembali">
            </div>
            <div class="coin-display" onclick="window.navigateToPage('/shop')">
                <span class="sprint-icon me-1"><img src="{{ asset_v('game_pacu/assets/image/ui/sprint.png') }}" alt="Sprint" style="width: 20px; height: 20px; object-fit: contain;"></span>
                <span class="coin-amount">{{ number_format($user->kuansing_poin, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Main Search Area -->
    <div class="search-container scrollable">
        <!-- Search Box -->
        <form action="/cari-pemain" method="GET" class="search-form" onsubmit="handleSearchSubmit(event)">
            <div class="search-input-wrapper">
                <i class="bi bi-search search-icon"></i>
                <input
                    type="text"
                    id="search-input-field"
                    name="search"
                    class="search-input"
                    placeholder="CARI PAMACU"
                    value="{{ $search }}"
                    autocomplete="off"
                    oninput="handleSearchInput(event)"
                >
                <button
                    type="button"
                    id="search-clear-btn"
                    class="search-clear-btn"
                    onclick="clearSearchInput()"
                    style="{{ $search ? 'display: flex;' : 'display: none;' }}"
                    title="Hapus pencarian"
                >
                    <i class="bi bi-x-circle-fill"></i>
                </button>
            </div>
        </form>

        <!-- Player List Header -->
        <div class="section-header" id="search-section-header">
            {{ $search ? 'Hasil Pencarian' : 'Rekomendasi Pemain' }}
        </div>

        <!-- Player Cards -->
        <div class="players-list" id="players-list-container">
            @include('page_game.caripemain._player_list')
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
{
    let searchDebounceTimer = null;

    window.goBack = function() {
        window.navigateToPage('/main-menu');
    };

    window.viewDetail = function(id) {
        window.navigateToPage('/cari-pemain/detail/' + id);
    };

    window.clearSearchInput = function() {
        const input = document.getElementById('search-input-field');
        const clearBtn = document.getElementById('search-clear-btn');
        if (input) {
            input.value = '';
            input.focus();
        }
        if (clearBtn) {
            clearBtn.style.display = 'none';
        }
        window.performPlayerSearch('');
    };

    window.performPlayerSearch = function(query) {
        const listContainer = document.getElementById('players-list-container');
        const headerEl = document.getElementById('search-section-header');
        if (headerEl) {
            headerEl.textContent = query ? 'Hasil Pencarian' : 'Rekomendasi Pemain';
        }
        if (!listContainer) return;

        fetch('/cari-pemain?search=' + encodeURIComponent(query), {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html'
            }
        })
        .then(response => response.text())
        .then(html => {
            listContainer.innerHTML = html;
        })
        .catch(err => {
            console.error('Search error:', err);
        });
    };

    window.handleSearchInput = function(e) {
        const query = e.target.value;
        const clearBtn = document.getElementById('search-clear-btn');
        if (clearBtn) {
            clearBtn.style.display = query.length > 0 ? 'flex' : 'none';
        }
        clearTimeout(searchDebounceTimer);
        searchDebounceTimer = setTimeout(() => {
            window.performPlayerSearch(query.trim());
        }, 300);
    };

    window.handleSearchSubmit = function(e) {
        if (e) e.preventDefault();
        const input = document.getElementById('search-input-field');
        const query = input ? input.value.trim() : '';
        clearTimeout(searchDebounceTimer);
        window.performPlayerSearch(query);
    };
}
</script>
@endpush
