<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sponsor;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;

class SponsorController extends Controller
{
    /**
     * Tampilkan daftar spanduk sponsor.
     */
    public function index()
    {
        $sponsors = Sponsor::orderBy('created_at', 'desc')->get();
        return view('pagesuperadmin.sponsors.index', compact('sponsors'));
    }

    /**
     * Simpan sponsor baru (dengan storage upload).
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'image' => 'required|image|mimes:png,jpg,jpeg,gif,webp|max:5120',
        ]);

        $disk = Storage::disk('public');

        $file     = $request->file('image');
        $ext      = strtolower($file->getClientOriginalExtension() ?: $file->guessExtension() ?: 'png');
        $filename = time() . '_' . Str::random(10) . '.' . $ext;
        $disk->putFileAs('sponsors', $file, $filename);

        Sponsor::create([
            'name'       => $request->name,
            'image_path' => 'storage/sponsors/' . $filename,
            'is_active'  => $request->boolean('is_active', true),
        ]);

        Alert::success('Berhasil', 'Spanduk sponsor berhasil ditambahkan.');
        return redirect()->back();
    }

    /**
     * Update sponsor (dengan atau tanpa ganti gambar).
     */
    public function update(Request $request, $id)
    {
        $sponsor = Sponsor::findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:255',
            'image' => 'nullable|image|mimes:png,jpg,jpeg,gif,webp|max:5120',
        ]);

        $data = [
            'name'      => $request->name,
            'is_active' => $request->boolean('is_active', true),
        ];

        if ($request->hasFile('image')) {
            $this->removeFile($sponsor->image_path);

            $disk     = Storage::disk('public');
            $file     = $request->file('image');
            $ext      = strtolower($file->getClientOriginalExtension() ?: $file->guessExtension() ?: 'png');
            $filename = time() . '_' . Str::random(10) . '.' . $ext;
            $disk->putFileAs('sponsors', $file, $filename);

            $data['image_path'] = 'storage/sponsors/' . $filename;
        }

        $sponsor->update($data);

        Alert::success('Berhasil', 'Spanduk sponsor berhasil diperbarui.');
        return redirect()->back();
    }

    /**
     * Hapus sponsor.
     */
    public function destroy($id)
    {
        $sponsor = Sponsor::findOrFail($id);

        $this->removeFile($sponsor->image_path);
        $sponsor->delete();

        Alert::success('Berhasil', 'Spanduk sponsor berhasil dihapus.');
        return redirect()->back();
    }

    /**
     * Toggle aktif/nonaktif sponsor.
     */
    public function toggleActive($id)
    {
        $sponsor            = Sponsor::findOrFail($id);
        $sponsor->is_active = !$sponsor->is_active;
        $sponsor->save();

        $status = $sponsor->is_active ? 'diaktifkan' : 'dinonaktifkan';
        Alert::success('Berhasil', "Sponsor \"{$sponsor->name}\" berhasil {$status}.");
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
