# 🏆 SPESIFIKASI IMPLEMENTASI SISTEM TURNAMEN PACU JALUR (SISTEM GUGUR)

> **Dokumen Perancangan & Rencana Kerja (Implementation Plan)**  
> **Project:** Pacu Jalur: The Pixel  
> **Tanggal:** 25 September 2026  
> **Status:** Menunggu Peninjauan & Persetujuan Admin / User  

---

## 📋 1. Ringkasan Fitur & Spesifikasi Kebutuhan

Sistem Turnamen ini dirancang menggunakan **Sistem Gugur (Single Elimination Bracket)** ala turnamen cup game sepakbola PS2 (seperti Winning Eleven / PES / Champions League Cup). Turnamen memiliki kontrol penuh dari Admin Dashboard, aturan batas waktu *Ready* 3 menit, integrasi penuh dengan arena pacu jalur berbasis Phaser & WebSocket, fitur penonton (*Spectator Live Preview*), serta piala/papan juara (*Hall of Fame*) untuk kebutuhan *show-off* profil pemain.

### 🔑 Poin Utama Kebutuhan:
1. **Kontrol Admin Eksklusif**: Hanya Admin dari **Dashboard SuperAdmin** yang dapat membuat, menentukan peserta yang ikut turnamen, mengacak *bracket*, dan memulai/mengelola turnamen.
2. **Sistem Gugur (Single Elimination)**: Skema bagan pertandingan (Quarter Final / Semi Final / Final). Pemenang otomatis maju ke babak berikutnya, yang kalah langsung gugur.
3. **Batas Waktu Ready 3 Menit (3-Minute Forfeit Rule)**: Jika pertandingan sudah gilirannya dan pemain tidak menekan tombol **SIAP** dalam 3 menit, pemain tersebut **dinyatakan GUGUR (WO / Forfeit)** dan lawannya otomatis lolos ke babak selanjutnya.
4. **Show Off / Database Winner (Hall of Fame)**: Pencatatan riwayat juara turnamen ke database agar gelar/trofi dapat ditampilkan di profil pemain dan leaderboard turnamen.
5. **Gameplay Identik dengan Arena Pacu**: Menggunakan sistem arena pacu jalur yang sudah ada di `resources/views/page_game/arenapacu/index.blade.php` (Phaser engine + WebSocket sync).
6. **Alur Kembali ke Bagan Turnamen**: Setelah selesai bertanding (Menang/Kalah), pemain diarahkan kembali ke menu turnamen untuk melihat susunan bagan pertandingan terbaru.
7. **Mode Penonton (Live Preview Spectator)**: Pemain lain yang sedang tidak bertanding dapat melihat (preview/nonton) pertandingan yang sedang berlangsung secara realtime melalui WebSocket streaming.

---

## 🗄️ 2. Perancangan Database (Migrations & Models)

Akan dibuat 4 tabel utama di database MySQL/MariaDB:

### 2.1 `tournaments`
Tabel master untuk menyimpan informasi turnamen yang dibuat oleh Admin.
- `id` (BigInt, PK, Auto Increment)
- `title` (String, Contoh: "Turnamen Pacu Jalur Akbar Q3 2026")
- `description` (Text, optional)
- `max_participants` (Enum: `4`, `8`, `16`, `32`)
- `status` (Enum: `draft`, `registration`, `active`, `completed`, `cancelled`) - Default: `draft`
- `prize_coins` (Unsigned BigInt, Default: 0)
- `winner_id` (Unsigned BigInt, Nullable, FK `users.id`)
- `runner_up_id` (Unsigned BigInt, Nullable, FK `users.id`)
- `started_at` (Timestamp, Nullable)
- `completed_at` (Timestamp, Nullable)
- `created_by` (Unsigned BigInt, FK `users.id`)
- `created_at` & `updated_at`

### 2.2 `tournament_participants`
Tabel daftar peserta yang ditentukan & dimasukkan oleh Admin ke dalam turnamen.
- `id` (BigInt, PK)
- `tournament_id` (FK `tournaments.id`, On Delete Cascade)
- `user_id` (FK `users.id`, On Delete Cascade)
- `seed_number` (Integer - Nomor urut bagan)
- `status` (Enum: `active`, `eliminated`, `winner`) - Default: `active`
- `final_rank` (Integer, Nullable - 1: Juara 1, 2: Juara 2, 3: Semifinalist, dst)
- `created_at` & `updated_at`

### 2.3 `tournament_matches`
Tabel jadwal & riwayat laga tiap babak dalam sistem gugur.
- `id` (BigInt, PK)
- `tournament_id` (FK `tournaments.id`, On Delete Cascade)
- `round` (Integer: 1 = Babak 16 besar / Perempatfinal, 2 = Semifinal, 3 = Final)
- `match_number` (Integer: urutan laga dalam babak)
- `player1_id` (FK `users.id`, Nullable)
- `player2_id` (FK `users.id`, Nullable)
- `winner_id` (FK `users.id`, Nullable)
- `status` (Enum: `waiting_players`, `ready_check`, `in_progress`, `completed`, `forfeited`)
- `ready_p1` (Boolean, Default: false)
- `ready_p2` (Boolean, Default: false)
- `ready_deadline` (Timestamp, Nullable - Berisi waktu expired 3 menit saat memasuki `ready_check`)
- `room_id` (String, Unique, Nullable - ID Room Arena Pacu)
- `spectator_count` (Integer, Default: 0)
- `created_at` & `updated_at`

### 2.4 `tournament_winners` (Hall of Fame)
Tabel riwayat pemenang untuk fitur *Show-Off* di Profil Pemain & Leaderboard.
- `id` (BigInt, PK)
- `tournament_id` (FK `tournaments.id`)
- `user_id` (FK `users.id`)
- `rank` (Integer: 1 = Juara 1, 2 = Juara 2, 3 = Juara 3)
- `tournament_title` (String)
- `trophy_badge` (String - contoh: `gold_trophy.png`, `silver_trophy.png`)
- `prize_coins` (BigInt)
- `created_at` & `updated_at`

---

## 👑 3. Fitur Admin Dashboard (SuperAdmin Control)

Akan ditambahkan menu baru **"Kelola Turnamen"** pada Dashboard SuperAdmin (`resources/views/pagesuperadmin/tournaments`).

```
[Dashboard SuperAdmin]
   └── 🏆 Turnamen
        ├── Create New Tournament (Judul, Hadiah Coin, Kapasitas 4/8/16/32)
        ├── Pilih & Daftarkan Pemain (Multi-select list user aktif)
        ├── Generate Bracket / Acak Bagan (Sistem Gugur)
        ├── Mulai Turnamen (Start Tournament)
        └── Kontrol Manual (Diskualifikasi, Reset, Selesaikan Turnamen)
```

### Flow Kerja Admin:
1. **Buat Turnamen**: Admin menginput nama turnamen dan jumlah slot peserta (misal: 8 peserta).
2. **Pilih Peserta**: Admin memilih user-user dari daftar akun terdaftar yang akan diikutkan dalam turnamen.
3. **Generate Bracket**: Admin menekan tombol **"Acak Bagan / Seed Bracket"**. Sistem secara otomatis menyusun skema pertandingan sistem gugur (Match 1: P1 vs P2, Match 2: P3 vs P4, dst).
4. **Start Turnamen**: Admin menekan **"Mulai Turnamen"**. Laga babak pertama berstatus `ready_check` dengan timer 3 menit.

---

## ⚔️ 4. Alur Pertandingan & Timer Ready 3 Menit

### 4.1 Alur Giliran Bertanding (Match Flow)
1. **Pemberitahuan Giliran**: Ketika babak turnamen dimulai atau babak sebelumnya selesai, match berikutnya berubah menjadi status `ready_check`.
2. **Hitung Mundur 3 Menit**: Column `ready_deadline` diisi `NOW() + 3 minutes`.
3. **Pemain Masuk ke Menu Turnamen**: Pemain yang gilirannya bertanding akan melihat tombol **"SIAP BERTANDING"** dengan timer 3 menit berjalan secara realtime.
4. **Kondisi Kesiapan**:
   - Jika **kedua pemain menekan SIAP** sebelum 3 menit: Sistem langsung membuat `room_id` khusus turnamen dan mengalihkan kedua pemain ke halaman `arena-pacu?tournament_match_id=...`.
   - Jika **salah satu pemain TIDAK SIAP dalam 3 menit**: Pemain yang tidak siap otomatis dinyatakan **GUGUR (FORFEIT)**. Pemain yang siap (atau pemain lawan) langsung dinyatakan **MENANG WO** dan berhak melaju ke babak selanjutnya.
   - Jika **kedua pemain TIDAK SIAP dalam 3 menit**: Sistem akan mengacak pemenang secara otomatis atau melakukan diskualifikasi ganda sesuai aturan admin.

### 4.2 Auto Check Timer (Cron / Node.js Worker)
Untuk memastikan aturan 3 menit berjalan tepat waktu tanpa harus direfresh:
- **WebSocket Server (`websocket-server.js`)**: Memiliki interval check setiap 5 detik untuk memeriksa laga `ready_check` yang telah melewati `ready_deadline`.
- **API Endpoint (`/api/tournament/check-timeouts`)**: Dipanggil untuk memproses keputusan diskualifikasi WO dan memperbarui bagan secara otomatis.

---

## 🎮 5. Integrasi Arena Pacu & Kembali ke Bagan (Return Flow)

```
 [ Menu Tournament (Bagan) ] 
            │
 (Kedua Pemain Ready)
            │
            ▼
 [ Halaman Arena Pacu ] ─── (Gameplay Phaser Boat Race Identik)
            │
  (Pertandingan Selesai)
            │
            ▼
 [ Auto Redirect Kembali ke Menu Tournament ] ─── (Bagan Terupdate & Pop-Up Hasil Laga)
```

1. **Gameplay Identik**: Menggunakan engine Phaser, grafik jalur pixel, dan sinkronisasi ketukan dayung yang sama dengan `arenapacu/index.blade.php`.
2. **Koneksi Room Khusus**: Pemain terhubung ke WebSocket Room `tournament_match_{id}`.
3. **Handling Game Finish**: Saat event `game_over` dipicu oleh Phaser engine:
   - Skor & pemenang dikirim via POST request ke `/tournament/match/{id}/finish`.
   - Database memperbarui `winner_id` match tersebut dan secara otomatis menempatkan pemenang ke slot babak berikutnya di tabel `tournament_matches`.
   - Pemain diarahkan kembali ke `/room/tournament/{tournament_id}` dengan efek UI selebrasi Kemenangan atau Pop-up Diskualifikasi/Kekalahan.

---

## 👁️ 6. Mode Penonton (Live Preview Spectator)

Di bagan turnamen PS2 Cup Style, pemain atau pengguna lain dapat menyaksikan laga yang sedang berlangsung:

1. **Tombol "TONTON / PREVIEW"**: Pada bagan pertandingan yang berstatus `in_progress`, muncul indikator **LIVE 🔴** dan tombol **"TONTON"**.
2. **Tampilan Spectator Arena**: Pemain spectator dialihkan ke `arena-pacu?tournament_match_id=...&mode=spectator`.
3. **Mekanisme Spectator WebSocket**:
   - UI dayung / kontrol ditekan disembunyikan.
   - Spectator hanya menerima broadcast `opponent_sync` & `game_state_sync` dari kedua pemain bertanding.
   - Phaser Canvas merepresentasikan pergerakan Perahu Player 1 (Jalur Kiri) dan Perahu Player 2 (Jalur Kanan) secara mulus (*smooth interpolation*).
   - Menampilkan jumlah penonton (*Live Spectators Count*).

---

## 🏆 7. Show Off / Hall of Fame Winner (Database Winner)

Pemain yang berhasil meraih Juara 1, 2, atau 3 dapat memamerkan pencapaian mereka:

1. **Tampilan di Profil Pemain (`/profil`)**:
   - Tab baru / Badge area: **"KOLEKSI TROFI TURNAMEN"**.
   - Menampilkan icon Piala Emas 🥇, Perak 🥈, dan Perunggu 🥉 berserta nama turnamen & tanggal kemenangan.
2. **Papan Juara Turnamen (`/tournament/hall-of-fame`)**:
   - Halaman khusus untuk melihat daftar Juara Turnamen sepanjang masa (*Historic Champions*).
   - Efek visual retro pixel art dengan animasi piala bersinar.

---

## 🖥️ 8. Desain Visual Bagan Turnamen (PS2 Cup Style Layout)

Halaman Turnamen di `resources/views/page_game/room/index.blade.php` (Slide 3 TOURNAMENT) akan membuka UI **Bagan Turnamen PS2 Style**:

```
 ┌──────────────┐                                       ┌──────────────┐
 │ Player 1 (W) │───┐                                 ┌───│ Player 5     │
 └──────────────┘   │   ┌──────────────┐   ┌────────┐ │   └──────────────┘
                    ├───│ Player 1     │──┐│ JUARA  │─┤
 ┌──────────────┐   │   └──────────────┘  ││  🏆    │ │   ┌──────────────┐
 │ Player 2     │───┘                     ├┤        ├─┼───│ Player 7     │
 └──────────────┘                         │└────────┘ │   └──────────────┘
                                          │           │
 ┌──────────────┐                         │           │   ┌──────────────┐
 │ Player 3     │───┐   ┌──────────────┐  │           └───│ Player 8     │
 └──────────────┘   ├───│ Player 3     │──┘               └──────────────┘
 ┌──────────────┐   │   └──────────────┘
 │ Player 4     │───┘
 └──────────────┘
  [ PEREMPAT FINAL ]      [ SEMI FINAL ]    [ FINAL ]     [ PEREMPAT FINAL ]
```

- Masing-masing kotak match berisi foto avatar, nama pemain, status (SIAP / BERTANDING / MENANG / WO / GUGUR), dan timer 3 menit jika sedang gilirannya.
- Desain menggunakan font *Press Start 2P*, gradasi neon retro, scanner scanline, dan backdrop khas PS5/PS2 menu.

---

## 🛠️ 9. Rencana Langkah Pekerjaan (Step-by-Step Execution Plan)

### **Tahap 1: Migration & Model Setup**
1. Membuat migration file `create_tournaments_table.php`, `create_tournament_participants_table.php`, `create_tournament_matches_table.php`, dan `create_tournament_winners_table.php`.
2. Membuat Model Eloquent: `Tournament`, `TournamentParticipant`, `TournamentMatch`, `TournamentWinner` dengan relasi lengkap.

### **Tahap 2: Dashboard SuperAdmin (Admin Control)**
1. Membuat `TournamentAdminController.php` di `app/Http/Controllers/superadmin`.
2. Membuat Blade view dashboard admin `resources/views/pagesuperadmin/tournaments/index.blade.php` & `create.blade.php`.
3. Mengimplementasikan fitur pembuatan turnamen, penyeleksian peserta oleh Admin, dan *Auto-Bracket Single Elimination Generator Logic*.

### **Tahap 3: Halaman & UI Turnamen Game (User Side)**
1. Membuat `TournamentController.php` di `app/Http/Controllers/pagegame`.
2. Memperbarui Slide TOURNAMENT di `resources/views/page_game/room/index.blade.php` untuk menampilkan daftar turnamen & bagan perlombaan (Bracket View).
3. Membuat komponen UI Bracket retro PS2 style.

### **Tahap 4: Logika Timer 3 Menit & Disqualified (Forfeit System)**
1. Menambahkan atribut `ready_deadline` dan fungsi penanganannya pada backend `TournamentController`.
2. Mengintegrasikan background check timer di `websocket-server.js` untuk mengeksekusi WO jika pemain *afk/timeout* 3 menit.

### **Tahap 5: Integrasi Arena Pacu & Flow Selesai Laga**
1. Modifikasi `ArenaPacuController.php` dan `arenapacu/index.blade.php` agar mendukung `tournament_match_id`.
2. Mengirimkan sinyal pemenang ke database pasca pertandingan & auto-redirect ke halaman bagan turnamen.

### **Tahap 6: Mode Penonton (Live Preview Spectator)**
1. Modifikasi `websocket-server.js` untuk broadcast event match ke penonton (role: `spectator`).
2. Menambahkan mode spectator di `arenapacu/index.blade.php` untuk merender pergerakan perahu tanpa tombol kontrol dayung.

### **Tahap 7: Hall of Fame & Trophy Profile**
1. Menambahkan tampilan piala & riwayat kemenangan di `resources/views/page_game/profil/index.blade.php`.
2. Membuat modal / halaman Hall of Fame Pemenang Turnamen.

---

## ❓ 10. Pertanyaan & Konfirmasi Sebelum Memulai Eksekusi Kode

Mohon tinjau rencana implementasi di atas. Silakan berikan masukan, revisi, atau persetujuan Anda:

1. **Jumlah Slot Peserta**: Apakah jumlah peserta turnamen standar yang Anda inginkan adalah **8 pemain** (Quarter -> Semi -> Final), atau fleksibel bisa dipilih Admin (4, 8, 16, 32 pemain)?
2. **Hadiah Coin**: Apakah pemenang turnamen otomatis mendapatkan Coin Hadiah dari total *prize pool* yang ditentukan Admin?
3. **Format Spectator**: Apakah penonton dapat mengirimkan Emote / Support Chat saat menonton match live?

---
*Dokumen ini dibuat secara otomatis dan siap untuk ditinjau oleh pengembang.*
