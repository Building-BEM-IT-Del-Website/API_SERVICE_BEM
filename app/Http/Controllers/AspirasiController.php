<?php

namespace App\Http\Controllers;

use App\Models\Aspirasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AspirasiController extends Controller
{
    /**
     * Display a listing of aspirasi.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user && $user->hasAnyRole(['admin', 'kemahasiswaan'])) {
            // Admin & Kemahasiswaan lihat semua aspirasi
            $aspirasis = Aspirasi::all();
        } else {
            // Publik/mahasiswa hanya lihat aspirasi yang sudah dipublikasikan
            $aspirasis = Aspirasi::whereIn('status', ['In Progress', 'Approved', 'Completed'])->get();
        }

        return response()->json([
            'success' => true,
            'message' => 'Daftar aspirasi',
            'data' => $aspirasis
        ], 200);
    }

    /**
     * Store a newly created aspirasi.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'nama' => 'nullable|string|max:255',
            'respon' => 'nullable|string',
        ]);

        // Status default dari migration adalah "Pending"
        $aspirasi = Aspirasi::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Aspirasi berhasil ditambahkan',
            'data' => $aspirasi
        ], 201);
    }

    /**
     * Show specific aspirasi.
     */
    public function show(Aspirasi $aspirasi)
{
    $user = Auth::user();

    if (!$user || !$user->hasAnyRole(['admin', 'kemahasiswaan'])) {
        return response()->json([
            'success' => false,
            'message' => 'Hanya admin dan kemahasiswaan yang dapat melihat aspirasi ini',
        ], 403);
    }

    return response()->json([
        'success' => true,
        'message' => 'Detail aspirasi',
        'data' => $aspirasi
    ], 200);
}


    /**
     * Update aspirasi.
     */
    public function update(Request $request, Aspirasi $aspirasi)
    {
        $data = $request->validate([
            // 'judul' => 'sometimes|required|string|max:255',
            // 'deskripsi' => 'sometimes|required|string',
            'status' => 'sometimes|required|in:Pending,Approved,Rejected,Completed,In Progress',
            // 'nama' => 'nullable|string|max:255',
            'respon' => 'nullable|string',
        ]);

        $user = Auth::user();

        // Jika ada field status, hanya admin yang bisa mengubah
        if (isset($data['status'])) {
            if (!$user || !$user->hasRole('admin')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya admin yang dapat mengubah status',
                ], 403);
            }
            $aspirasi->status = $data['status'];
            $aspirasi->read_by = $user->id; // Catat siapa admin yang mengubah
            unset($data['status']); // hapus dari array agar tidak diproses lagi
        }

        // Update field lainnya
        $aspirasi->update($data);
        $aspirasi->save();

        return response()->json([
            'success' => true,
            'message' => 'Aspirasi berhasil diperbarui',
            'data' => $aspirasi
        ], 200);
    }

    /**
     * Soft delete aspirasi.
     */
    public function destroy(Aspirasi $aspirasi)
    {
        $user = Auth::user();
        if (!$user || !$user->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya admin yang dapat menghapus aspirasi',
            ], 403);
        }

        $aspirasi->delete();

        return response()->json([
            'success' => true,
            'message' => 'Aspirasi berhasil dihapus (soft delete)',
            'data' => null
        ], 200);
    }

    /**
     * Show trashed aspirasi.
     */
    public function trashed()
    {
        $user = Auth::user();
        if (!$user || !$user->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya admin yang dapat melihat aspirasi terhapus',
            ], 403);
        }

        $aspirasis = Aspirasi::onlyTrashed()->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar aspirasi yang terhapus',
            'data' => $aspirasis
        ], 200);
    }

    /**
     * Restore aspirasi dari trash.
     */
    public function restore($id)
    {
        $user = Auth::user();
        if (!$user || !$user->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya admin yang dapat merestore aspirasi',
            ], 403);
        }

        $aspirasi = Aspirasi::onlyTrashed()->find($id);
        if (!$aspirasi) {
            return response()->json([
                'success' => false,
                'message' => 'Aspirasi tidak ditemukan di trash',
                'data' => null
            ], 404);
        }

        $aspirasi->restore();

        return response()->json([
            'success' => true,
            'message' => 'Aspirasi berhasil direstore',
            'data' => $aspirasi
        ], 200);
    }

    /**
     * Permanently delete aspirasi.
     */
    public function forceDelete($id)
    {
        $user = Auth::user();
        if (!$user || !$user->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => 'Hanya admin yang dapat menghapus permanen aspirasi',
            ], 403);
        }

        $aspirasi = Aspirasi::onlyTrashed()->find($id);
        if (!$aspirasi) {
            return response()->json([
                'success' => false,
                'message' => 'Aspirasi tidak ditemukan di trash',
                'data' => null
            ], 404);
        }

        $aspirasi->forceDelete();

        return response()->json([
            'success' => true,
            'message' => 'Aspirasi berhasil dihapus permanen',
            'data' => null
        ], 200);
    }
}
