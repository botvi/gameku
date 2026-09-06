@if (empty($players) || count($players) == 0)
    <div class="players-empty">
        <div class="empty-icon"><i class="bi bi-search text-secondary" style="font-size: 28px;"></i></div>
        <div class="empty-title">TIDAK DITEMUKAN</div>
        <div class="empty-subtitle">Silakan cari dengan kata kunci lain.</div>
    </div>
@else
    @foreach ($players as $player)
        @php
        $playerWins = $player->wins()->count();
        $dbAvatar = $player->foto_profile;
        if (!empty($dbAvatar)) {
            if (strpos($dbAvatar, 'http://') === 0 || strpos($dbAvatar, 'https://') === 0) {
                $avatarUrl = $dbAvatar;
            } elseif (strpos($dbAvatar, '/') !== false || strpos($dbAvatar, '.gif') !== false) {
                $avatarUrl = (strpos($dbAvatar, '/') === 0) ? $dbAvatar : '/' . $dbAvatar;
            } else {
                $avatarUrl = 'game_pacu/assets/image/ui/' . $dbAvatar . '.gif';
            }
        } else {
            $avatarUrl = 'game_pacu/assets/image/ui/profil.gif';
        }
        @endphp
        <div class="player-row">
            <div class="player-info">
                <div class="player-avatar-wrapper">
                    <img src="{{ asset_v($avatarUrl) }}" alt="Avatar" class="player-avatar-img">
                </div>
                <div class="player-details">
                    <div class="player-name">{{ $player->nama_jalur ?? $player->email }}</div>
                    <div class="player-wins"><i class="bi bi-trophy-fill text-warning me-1"></i>{{ $playerWins }} Wins</div>
                </div>
            </div>
            <button class="detail-btn" onclick="viewDetail({{ $player->id }})">PROFIL</button>
        </div>
    @endforeach
@endif
