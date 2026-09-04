<?php

use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\{
    DashboardController,
};

use App\Http\Controllers\superadmin\{
    DashboardSuperAdminController,
    ShopItemController,
    SponsorController,
    InboxController,
};

use App\Http\Controllers\auth\{
    LoginController,
    GoogleController,
};

use App\Http\Controllers\pagegame\{
    MainMenuController,
    ArenaPacuController,
    ProfilController,
    RoomController,
    ShopController,
    TukangJaluarController,
    SplahScreenController,
    VsAiController,
    LeaderboardController,
    InboxGameController,
    LoadingController,
};

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Manual
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');


// splash
Route::get('/', [SplahScreenController::class, 'index'])->name('splash');

// Google
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);
Route::get('/auth/google/complete', [GoogleController::class, 'showCompleteForm'])->name('google.complete');
Route::post('/auth/google/complete-register', [GoogleController::class, 'completeRegister'])->name('google.complete.register');

Route::group(['middleware' => ['auth', 'role:admin', 'check.blocked']], function () {
    Route::get('/dashboard-superadmin', [DashboardSuperAdminController::class, 'index'])->name('dashboard-superadmin');

    // User management
    Route::get('/dashboard-superadmin/users', [DashboardSuperAdminController::class, 'users'])->name('superadmin.users');
    Route::post('/dashboard-superadmin/users/{id}/toggle-block', [DashboardSuperAdminController::class, 'toggleBlock'])->name('superadmin.users.toggle-block');

    // Settings management (API Key & Merchant ID)
    Route::get('/dashboard-superadmin/settings', [DashboardSuperAdminController::class, 'settings'])->name('superadmin.settings');
    Route::post('/dashboard-superadmin/settings/save', [DashboardSuperAdminController::class, 'saveSettings'])->name('superadmin.settings.save');

    // Coin packages CRUD
    Route::get('/dashboard-superadmin/coin-packages', [DashboardSuperAdminController::class, 'packages'])->name('superadmin.packages');
    Route::post('/dashboard-superadmin/coin-packages/store', [DashboardSuperAdminController::class, 'storePackage'])->name('superadmin.packages.store');
    Route::post('/dashboard-superadmin/coin-packages/{id}/update', [DashboardSuperAdminController::class, 'updatePackage'])->name('superadmin.packages.update');
    Route::post('/dashboard-superadmin/coin-packages/{id}/delete', [DashboardSuperAdminController::class, 'deletePackage'])->name('superadmin.packages.delete');

    // Transactions history
    Route::get('/dashboard-superadmin/transactions', [DashboardSuperAdminController::class, 'transactions'])->name('superadmin.transactions');

    // Shop Items CRUD
    Route::get('/dashboard-superadmin/shop-items', [ShopItemController::class, 'index'])->name('superadmin.shop-items');
    Route::post('/dashboard-superadmin/shop-items/store', [ShopItemController::class, 'store'])->name('superadmin.shop-items.store');
    Route::post('/dashboard-superadmin/shop-items/{id}/update', [ShopItemController::class, 'update'])->name('superadmin.shop-items.update');
    Route::post('/dashboard-superadmin/shop-items/{id}/delete', [ShopItemController::class, 'destroy'])->name('superadmin.shop-items.delete');
    Route::post('/dashboard-superadmin/shop-items/{id}/toggle', [ShopItemController::class, 'toggleActive'])->name('superadmin.shop-items.toggle');

    // Sponsors CRUD
    Route::get('/dashboard-superadmin/sponsors', [SponsorController::class, 'index'])->name('superadmin.sponsors');
    Route::post('/dashboard-superadmin/sponsors/store', [SponsorController::class, 'store'])->name('superadmin.sponsors.store');
    Route::post('/dashboard-superadmin/sponsors/{id}/update', [SponsorController::class, 'update'])->name('superadmin.sponsors.update');
    Route::post('/dashboard-superadmin/sponsors/{id}/delete', [SponsorController::class, 'destroy'])->name('superadmin.sponsors.delete');
    Route::post('/dashboard-superadmin/sponsors/{id}/toggle', [SponsorController::class, 'toggleActive'])->name('superadmin.sponsors.toggle');

    // Inbox CRUD
    Route::get('/dashboard-superadmin/inbox', [InboxController::class, 'index'])->name('superadmin.inbox');
    Route::post('/dashboard-superadmin/inbox/store', [InboxController::class, 'store'])->name('superadmin.inbox.store');
    Route::post('/dashboard-superadmin/inbox/{id}/update', [InboxController::class, 'update'])->name('superadmin.inbox.update');
    Route::post('/dashboard-superadmin/inbox/{id}/delete', [InboxController::class, 'destroy'])->name('superadmin.inbox.delete');
    Route::post('/dashboard-superadmin/inbox/{id}/toggle', [InboxController::class, 'toggleActive'])->name('superadmin.inbox.toggle');
});

// Webhook KlikQRIS (no CSRF, public)
Route::post('/webhook/klikqris', [\App\Http\Controllers\pagegame\TopupController::class, 'webhook'])->name('klikqris.webhook');

Route::middleware(['auth', 'check.blocked'])->group(function () {
    Route::get('/loading', [LoadingController::class, 'index'])->name('loading');
    Route::get('/api/player/sync-data', [LoadingController::class, 'syncData'])->name('api.player.sync-data');
    Route::get('/main-menu', [MainMenuController::class, 'index'])->name('main-menu');
    Route::get('/arena-pacu', [ArenaPacuController::class, 'index'])->name('arena-pacu');
    Route::post('/arena-pacu/add-coins', [ArenaPacuController::class, 'addCoins'])->name('arena-pacu.add-coins');
    Route::get('/profil', [ProfilController::class, 'index'])->name('profil');
    Route::get('/room', [RoomController::class, 'index'])->name('room');
    Route::get('/room/create-or-join', [RoomController::class, 'createOrJoin'])->name('room.create-or-join');
    Route::post('/room/create', [RoomController::class, 'create'])->name('room.create');
    Route::post('/room/join', [RoomController::class, 'join'])->name('room.join');
    Route::post('/room/join-by-code', [RoomController::class, 'joinByCode'])->name('room.join-by-code');
    Route::post('/room/matchmake', [RoomController::class, 'matchmake'])->name('room.matchmake');
    Route::post('/room/matchmake/status', [RoomController::class, 'matchmakeStatus'])->name('room.matchmake.status');
    Route::post('/room/matchmake/cancel', [RoomController::class, 'matchmakeCancel'])->name('room.matchmake.cancel');
    Route::get('/room/lobby/{id}', [RoomController::class, 'lobby'])->name('room.lobby');
    Route::post('/room/ready', [RoomController::class, 'ready'])->name('room.ready');
    Route::post('/room/leave', [RoomController::class, 'leave'])->name('room.leave');
    Route::post('/room/finish', [RoomController::class, 'finish'])->name('room.finish');
    Route::get('/room/list', [RoomController::class, 'list'])->name('room.list');
    Route::get('/shop', [ShopController::class, 'index'])->name('shop');
    Route::post('/shop/add-points', [ShopController::class, 'addPoints'])->name('shop.add-points');
    Route::post('/shop/buy-item', [ShopController::class, 'buyItem'])->name('shop.buy-item');
    Route::get('/shop/preview-item/{id}', [ShopController::class, 'previewItem'])->name('shop.preview-item');
    Route::get('/shop/download-item/{id}', [ShopController::class, 'downloadItem'])->name('shop.download-item');
    Route::get('/tukang-jaluar', [TukangJaluarController::class, 'index'])->name('tukang-jaluar');
    Route::post('/tukang-jaluar/save', [TukangJaluarController::class, 'save'])->name('tukang-jaluar.save');
    Route::get('/tukang-jaluar/get', [TukangJaluarController::class, 'get'])->name('tukang-jaluar.get');
    Route::post('/tukang-jaluar/upload-corak', [TukangJaluarController::class, 'uploadCorak'])->name('tukang-jaluar.upload-corak');
    Route::post('/tukang-jaluar/upload-lambai', [TukangJaluarController::class, 'uploadLambai'])->name('tukang-jaluar.upload-lambai');
    Route::get('/cari-pemain', [\App\Http\Controllers\pagegame\CariPemainController::class, 'index'])->name('cari-pemain');
    Route::get('/cari-pemain/detail/{id}', [\App\Http\Controllers\pagegame\CariPemainController::class, 'detail'])->name('cari-pemain.detail');
    // VS AI
    Route::get('/vsai/level', [VsAiController::class, 'level'])->name('vsai.level');
    Route::get('/vsai/arena', [VsAiController::class, 'arena'])->name('vsai.arena');
    Route::post('/vsai/add-coins', [VsAiController::class, 'addCoins'])->name('vsai.add-coins');
    // Leaderboard
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');

    // Inbox User Routes
    Route::get('/inbox/list', [InboxGameController::class, 'list'])->name('inbox.list');
    Route::post('/inbox/{id}/read', [InboxGameController::class, 'markAsRead'])->name('inbox.read');
    Route::post('/inbox/{id}/claim', [InboxGameController::class, 'claimReward'])->name('inbox.claim');
    Route::post('/inbox/{id}/delete', [InboxGameController::class, 'deleteInbox'])->name('inbox.delete');

    // Topup & QRIS Snap
    Route::post('/topup/create', [\App\Http\Controllers\pagegame\TopupController::class, 'createTransaction'])->name('topup.create');
    Route::get('/topup/status/{order_id}', [\App\Http\Controllers\pagegame\TopupController::class, 'checkStatus'])->name('topup.status');
});
