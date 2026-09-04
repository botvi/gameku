<?php

namespace App\Http\Controllers\superadmin;

use App\Http\Controllers\Controller;
use App\Models\Inbox;
use App\Models\User;
use Illuminate\Http\Request;

class InboxController extends Controller
{
    public function index()
    {
        $inboxes = Inbox::with('targetUser')
            ->orderBy('created_at', 'desc')
            ->get();

        $users = User::where('role', '!=', 'admin')
            ->orderBy('nama_jalur', 'asc')
            ->orderBy('email', 'asc')
            ->get();

        return view('pagesuperadmin.inbox.index', compact('inboxes', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:150',
            'content' => 'required|string|max:2000',
            'type' => 'required|in:info,reward,announcement,warning',
            'target_type' => 'required|in:all,user',
            'target_user_id' => 'required_if:target_type,user|nullable|exists:users,id',
            'reward_coins' => 'nullable|integer|min:0',
        ], [
            'title.required' => 'Judul inbox wajib diisi.',
            'content.required' => 'Isi pesan inbox wajib diisi.',
            'type.required' => 'Pilih tipe pesan inbox.',
            'target_type.required' => 'Pilih target penerima inbox.',
            'target_user_id.required_if' => 'Pilih player penerima jika memilih target player spesifik.',
        ]);

        Inbox::create([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'type' => $request->input('type'),
            'target_type' => $request->input('target_type'),
            'target_user_id' => $request->input('target_type') === 'user' ? $request->input('target_user_id') : null,
            'reward_coins' => $request->input('reward_coins', 0),
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->back()->with('success', 'Pesan Inbox berhasil dikirim / ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $inbox = Inbox::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:150',
            'content' => 'required|string|max:2000',
            'type' => 'required|in:info,reward,announcement,warning',
            'target_type' => 'required|in:all,user',
            'target_user_id' => 'required_if:target_type,user|nullable|exists:users,id',
            'reward_coins' => 'nullable|integer|min:0',
        ], [
            'title.required' => 'Judul inbox wajib diisi.',
            'content.required' => 'Isi pesan inbox wajib diisi.',
            'type.required' => 'Pilih tipe pesan inbox.',
        ]);

        $inbox->update([
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'type' => $request->input('type'),
            'target_type' => $request->input('target_type'),
            'target_user_id' => $request->input('target_type') === 'user' ? $request->input('target_user_id') : null,
            'reward_coins' => $request->input('reward_coins', 0),
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->back()->with('success', 'Pesan Inbox berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $inbox = Inbox::findOrFail($id);
        $inbox->delete();

        return redirect()->back()->with('success', 'Pesan Inbox berhasil dihapus!');
    }

    public function toggleActive($id)
    {
        $inbox = Inbox::findOrFail($id);
        $inbox->is_active = !$inbox->is_active;
        $inbox->save();

        $statusStr = $inbox->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', "Pesan inbox berhasil {$statusStr}!");
    }
}
