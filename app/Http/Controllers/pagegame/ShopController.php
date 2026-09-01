<?php

namespace App\Http\Controllers\pagegame;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CoinPackage;
use App\Models\ShopItem;

class ShopController extends Controller
{
    public function index()
    {
        $packages  = CoinPackage::orderBy('coin_amount', 'asc')->get();
        $shopItems = ShopItem::where('is_active', true)->orderBy('price_kp', 'asc')->get();

        // Ambil ID item yang sudah dibeli oleh user ini
        $user            = auth()->user();
        $purchasedIds    = $user ? $user->purchasedShopItems()->pluck('shop_items.id')->toArray() : [];

        return view('page_game.shop.index', compact('packages', 'shopItems', 'purchasedIds'));
    }

    public function addPoints(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $points = intval($request->input('points', 0));
        if ($points > 0) {
            $user->kuansing_poin += $points;
            $user->save();
        }

        return response()->json([
            'success'       => true,
            'kuansing_poin' => $user->kuansing_poin
        ]);
    }

    /**
     * User membeli item dari shop.
     * Kurangi KP user, catat di tabel pivot.
     */
    public function buyItem(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $itemId = $request->input('item_id');
        $item   = ShopItem::where('id', $itemId)->where('is_active', true)->first();

        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Item tidak ditemukan.'], 404);
        }

        // Cek sudah pernah beli
        if ($user->purchasedShopItems()->where('shop_items.id', $item->id)->exists()) {
            return response()->json(['success' => false, 'message' => 'Item sudah dimiliki.'], 409);
        }

        // Cek koin cukup
        if ($user->kuansing_poin < $item->price_kp) {
            return response()->json(['success' => false, 'message' => 'KP tidak cukup.'], 400);
        }

        // Kurangi KP dan catat pembelian
        $user->kuansing_poin -= $item->price_kp;
        $user->save();

        $user->purchasedShopItems()->attach($item->id);

        return response()->json([
            'success'       => true,
            'message'       => 'Pembelian berhasil!',
            'kuansing_poin' => $user->kuansing_poin,
            'download_url'  => '/' . $item->image_path,
            'filename'      => $item->filename,
        ]);
    }

    /**
     * Download gambar item yang sudah dibeli.
     */
    public function downloadItem($id)
    {
        $user = auth()->user();
        if (!$user) {
            abort(401);
        }

        $item = ShopItem::findOrFail($id);

        // Cek apakah user sudah membeli item ini
        if (!$user->purchasedShopItems()->where('shop_items.id', $item->id)->exists()) {
            abort(403, 'Anda belum memiliki item ini.');
        }

        $filePath = public_path($item->image_path);
        if (!file_exists($filePath)) {
            abort(404, 'File tidak ditemukan.');
        }

        return response()->download($filePath, $item->filename);
    }
}
