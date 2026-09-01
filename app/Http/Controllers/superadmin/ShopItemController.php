<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShopItem;
use RealRashid\SweetAlert\Facades\Alert;

class ShopItemController extends Controller
{
    /**
     * Tampilkan daftar item shop.
     */
    public function index()
    {
        $items = ShopItem::orderBy('created_at', 'desc')->get();
        return view('pagesuperadmin.shop_items.index', compact('items'));
    }

    /**
     * Simpan item baru (dengan upload gambar).
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'price_kp'    => 'required|integer|min:1',
            'image'       => 'required|image|mimes:png,jpg,jpeg,gif,webp|max:5120',
        ]);

        $file     = $request->file('image');
        $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());

        $dir = public_path('shop_items');
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        $file->move($dir, $filename);

        ShopItem::create([
            'name'        => $request->name,
            'description' => $request->description,
            'price_kp'    => $request->price_kp,
            'image_path'  => 'shop_items/' . $filename,
            'filename'    => $filename,
            'is_active'   => $request->boolean('is_active', true),
        ]);

        Alert::success('Berhasil', 'Item shop berhasil ditambahkan.');
        return redirect()->back();
    }

    /**
     * Update item (dengan atau tanpa ganti gambar).
     */
    public function update(Request $request, $id)
    {
        $item = ShopItem::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'price_kp'    => 'required|integer|min:1',
            'image'       => 'nullable|image|mimes:png,jpg,jpeg,gif,webp|max:5120',
        ]);

        $data = [
            'name'        => $request->name,
            'description' => $request->description,
            'price_kp'    => $request->price_kp,
            'is_active'   => $request->boolean('is_active', true),
        ];

        if ($request->hasFile('image')) {
            // Hapus gambar lama
            $oldPath = public_path($item->image_path);
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }

            $file     = $request->file('image');
            $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());

            $dir = public_path('shop_items');
            if (!file_exists($dir)) {
                mkdir($dir, 0755, true);
            }

            $file->move($dir, $filename);

            $data['image_path'] = 'shop_items/' . $filename;
            $data['filename']   = $filename;
        }

        $item->update($data);

        Alert::success('Berhasil', 'Item shop berhasil diperbarui.');
        return redirect()->back();
    }

    /**
     * Hapus item.
     */
    public function destroy($id)
    {
        $item = ShopItem::findOrFail($id);

        // Hapus file gambar
        $oldPath = public_path($item->image_path);
        if (file_exists($oldPath)) {
            unlink($oldPath);
        }

        $item->delete();

        Alert::success('Berhasil', 'Item shop berhasil dihapus.');
        return redirect()->back();
    }

    /**
     * Toggle aktif/nonaktif item.
     */
    public function toggleActive($id)
    {
        $item            = ShopItem::findOrFail($id);
        $item->is_active = !$item->is_active;
        $item->save();

        $status = $item->is_active ? 'diaktifkan' : 'dinonaktifkan';
        Alert::success('Berhasil', "Item \"{$item->name}\" berhasil {$status}.");
        return redirect()->back();
    }
}
