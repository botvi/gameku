<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShopItem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
     * Simpan item baru (dengan storage upload & thumbnail 1:1).
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'price_kp'    => 'required|integer|min:1',
            'image'       => 'required|file|max:10240',
            'thumbnail'   => 'nullable|image|mimes:png,jpg,jpeg,gif,webp|max:5120',
        ]);

        $disk = Storage::disk('public');

        // Upload item asset file (file asli untuk download)
        $file     = $request->file('image');
        $ext      = strtolower($file->getClientOriginalExtension() ?: $file->guessExtension() ?: 'png');
        $filename = time() . '_' . Str::random(10) . '.' . $ext;
        $disk->putFileAs('shop_items', $file, $filename);
        $imagePath = 'storage/shop_items/' . $filename;

        // Upload thumbnail (gambar 1:1 untuk game & preview)
        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbFile     = $request->file('thumbnail');
            $thumbExt      = strtolower($thumbFile->getClientOriginalExtension() ?: $thumbFile->guessExtension() ?: 'png');
            $thumbFilename = time() . '_thumb_' . Str::random(10) . '.' . $thumbExt;
            $disk->putFileAs('shop_items/thumbnails', $thumbFile, $thumbFilename);
            $thumbnailPath = 'storage/shop_items/thumbnails/' . $thumbFilename;
        }

        ShopItem::create([
            'name'           => $request->name,
            'description'    => $request->description,
            'price_kp'       => $request->price_kp,
            'image_path'     => $imagePath,
            'thumbnail_path' => $thumbnailPath,
            'filename'       => $file->getClientOriginalName() ?: $filename,
            'is_active'      => $request->boolean('is_active', true),
        ]);

        Alert::success('Berhasil', 'Item shop berhasil ditambahkan.');
        return redirect()->back();
    }

    /**
     * Update item (dengan atau tanpa ganti file/thumbnail).
     */
    public function update(Request $request, $id)
    {
        $item = ShopItem::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string|max:500',
            'price_kp'    => 'required|integer|min:1',
            'image'       => 'nullable|file|max:10240',
            'thumbnail'   => 'nullable|image|mimes:png,jpg,jpeg,gif,webp|max:5120',
        ]);

        $data = [
            'name'        => $request->name,
            'description' => $request->description,
            'price_kp'    => $request->price_kp,
            'is_active'   => $request->boolean('is_active', true),
        ];

        $disk = Storage::disk('public');

        if ($request->hasFile('image')) {
            $this->removeFile($item->image_path);

            $file     = $request->file('image');
            $ext      = strtolower($file->getClientOriginalExtension() ?: $file->guessExtension() ?: 'png');
            $filename = time() . '_' . Str::random(10) . '.' . $ext;
            $disk->putFileAs('shop_items', $file, $filename);

            $data['image_path'] = 'storage/shop_items/' . $filename;
            $data['filename']   = $file->getClientOriginalName() ?: $filename;
        }

        if ($request->hasFile('thumbnail')) {
            if (!empty($item->thumbnail_path)) {
                $this->removeFile($item->thumbnail_path);
            }

            $thumbFile     = $request->file('thumbnail');
            $thumbExt      = strtolower($thumbFile->getClientOriginalExtension() ?: $thumbFile->guessExtension() ?: 'png');
            $thumbFilename = time() . '_thumb_' . Str::random(10) . '.' . $thumbExt;
            $disk->putFileAs('shop_items/thumbnails', $thumbFile, $thumbFilename);

            $data['thumbnail_path'] = 'storage/shop_items/thumbnails/' . $thumbFilename;
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

        $this->removeFile($item->image_path);
        if (!empty($item->thumbnail_path)) {
            $this->removeFile($item->thumbnail_path);
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

    /**
     * Hapus file fisik dari storage public atau public_path.
     */
    private function removeFile(?string $path): void
    {
        if (empty($path)) {
            return;
        }

        $relativeStoragePath = preg_replace('#^storage/#', '', $path);
        if (Storage::disk('public')->exists($relativeStoragePath)) {
            Storage::disk('public')->delete($relativeStoragePath);
        }

        $publicPath = public_path($path);
        if (file_exists($publicPath) && !is_dir($publicPath)) {
            @unlink($publicPath);
        }
    }
}
