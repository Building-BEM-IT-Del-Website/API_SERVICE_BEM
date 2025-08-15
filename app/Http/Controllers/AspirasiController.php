<?php

namespace App\Http\Controllers;

use App\Models\Aspirasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AspirasiController extends Controller
{
    /**
     * Display a listing of aspirasi.
     * Logika ini tetap di sini karena berbeda antara user terautentikasi dan tamu.
     */
    public function index()
    {
        // 'can' untuk periksa permission, bukan role. Lebih fleksibel.
        if (Auth::check() && Auth::user()->can('lihat_aspirasi')) {
            // Admin & Kemahasiswaan lihat semua aspirasi
            $aspirasis = Aspirasi::latest()->get();
        } else {
            // Publik/mahasiswa hanya lihat aspirasi yang sudah dipublikasikan
            $aspirasis = Aspirasi::whereIn('status', ['In Progress', 'Approved', 'Completed'])->latest()->get();
        }

        return response()->json([
            'success' => true,
            'message' => 'Daftar aspirasi berhasil diambil',
            'data' => $aspirasis
        ], 200);
    }

    /**
     * Store a newly created aspirasi.
     * Tidak ada cek otorisasi, karena ini publik.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'nama' => 'nullable|string|max:255',
        ]);

        $aspirasi = Aspirasi::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Aspirasi berhasil ditambahkan',
            'data' => $aspirasi
        ], 201);
    }

    /**
     * Show specific aspirasi.
     * Cek otorisasi sudah dihandle oleh middleware 'permission:lihat_aspirasi'.
     */
    public function show(Aspirasi $aspirasi)
    {
        return response()->json([
            'success' => true,
            'message' => 'Detail aspirasi',
            'data' => $aspirasi
        ], 200);
    }

    /**
     * Update aspirasi.
     * Cek otorisasi sudah dihandle oleh middleware 'permission:kelola_aspirasi'.
     */
    public function update(Request $request, Aspirasi $aspirasi)
    {
        $data = $request->validate([
            'status' => 'sometimes|required|in:Pending,Approved,Rejected,Completed,In Progress',
            'respon' => 'nullable|string',
        ]);

        $user = Auth::user();

        // Logika bisnis tetap ada, tapi cek role sudah hilang
        if (isset($data['status'])) {
            $aspirasi->status = $data['status'];
            $aspirasi->read_by = $user->id; 
        }

        if (isset($data['respon'])) {
             $aspirasi->respon = $data['respon'];
        }
        
        $aspirasi->save();

        return response()->json([
            'success' => true,
            'message' => 'Aspirasi berhasil diperbarui',
            'data' => $aspirasi
        ], 200);
    }

    /**
     * Soft delete aspirasi.
     * Cek otorisasi sudah dihandle oleh middleware 'permission:kelola_aspirasi'.
     */
    public function destroy(Aspirasi $aspirasi)
    {
        $aspirasi->delete();

        return response()->json([
            'success' => true,
            'message' => 'Aspirasi berhasil dihapus (soft delete)',
        ], 200);
    }

    /**
     * Show trashed aspirasi.
     * Cek otorisasi sudah dihandle oleh middleware 'permission:kelola_aspirasi'.
     */
    public function trashed()
    {
        $aspirasis = Aspirasi::onlyTrashed()->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar aspirasi yang terhapus',
            'data' => $aspirasis
        ], 200);
    }

    /**
     * Restore aspirasi dari trash.
     * Cek otorisasi sudah dihandle oleh middleware 'permission:kelola_aspirasi'.
     */
    public function restore($id)
    {
        $aspirasi = Aspirasi::onlyTrashed()->findOrFail($id);
        $aspirasi->restore();

        return response()->json([
            'success' => true,
            'message' => 'Aspirasi berhasil direstore',
            'data' => $aspirasi
        ], 200);
    }

    /**
     * Permanently delete aspirasi.
     * Cek otorisasi sudah dihandle oleh middleware 'permission:kelola_aspirasi'.
     */
    public function forceDelete($id)
    {
        $aspirasi = Aspirasi::onlyTrashed()->findOrFail($id);
        $aspirasi->forceDelete();

        return response()->json([
            'success' => true,
            'message' => 'Aspirasi berhasil dihapus permanen',
        ], 200);
    }
}