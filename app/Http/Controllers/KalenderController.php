<?php

namespace App\Http\Controllers;

use App\Models\Kalender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KalenderController extends Controller
{
    /**
     * Menampilkan daftar semua kegiatan kalender.
     * Endpoint ini bisa diakses publik.
     */
    public function index()
    {
        $data = Kalender::with('creator')->latest()->get();
        
        return response()->json([
            'success' => true,
            'message' => 'Daftar kalender berhasil diambil',
            'data' => $data
        ], 200);
    }

    /**
     * Menyimpan kegiatan kalender baru.
     * Cek otorisasi dihandle oleh middleware 'permission:kelola_kalender'.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_berakhir' => 'nullable|date|after_or_equal:tanggal_mulai',
            'sumber' => 'required|array', // 'sumber' adalah sebuah array
            'sumber.*' => 'string|max:50', //setiap item di dalam array 'sumber'
        ]);

        // Tambahkan ID user yang sedang login secara otomatis
        $validatedData['create_by'] = Auth::id();

        $kalender = Kalender::create($validatedData);
        $kalender->load('creator'); // Muat relasi agar data di response lengkap

        return response()->json([
            'success' => true,
            'message' => 'Kegiatan kalender berhasil ditambahkan',
            'data' => $kalender
        ], 201); 
    }

    /**
     * Menampilkan satu kegiatan kalender spesifik.
     * Endpoint ini bisa diakses publik.
     */
    public function show(Kalender $kalender)
    {
        $kalender->load('creator');

        return response()->json([
            'success' => true,
            'message' => 'Detail kalender berhasil diambil',
            'data' => $kalender
        ], 200);
    }

    /**
     * Memperbarui kegiatan kalender.
     * Cek otorisasi dihandle oleh middleware 'permission:kelola_kalender'.
     */
    public function update(Request $request, Kalender $kalender)
    {
        $validatedData = $request->validate([
            'judul' => 'sometimes|required|string|max:255',
            'deskripsi' => 'sometimes|nullable|string',
            'tanggal_mulai' => 'sometimes|required|date',
            'tanggal_berakhir' => 'sometimes|nullable|date|after_or_equal:tanggal_mulai',
            'sumber' => 'sometimes|required|array',
            'sumber.*' => 'string|max:50',
        ]);

        $kalender->update($validatedData);
        $kalender->load('creator');

        return response()->json([
            'success' => true,
            'message' => 'Kegiatan kalender berhasil diperbarui',
            'data' => $kalender
        ], 200);
    }

    /**
     * Menghapus kegiatan kalender (soft delete).
     * Cek otorisasi dihandle oleh middleware 'permission:kelola_kalender'.
     */
    public function destroy(Kalender $kalender)
    {
        $kalender->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kegiatan kalender berhasil dihapus (soft delete)',
        ], 200);
    }

    /**
     * Menampilkan daftar kegiatan yang sudah di-soft delete.
     * Cek otorisasi dihandle oleh middleware 'permission:kelola_kalender'.
     */
    public function trashed()
    {
        $kalenders = Kalender::onlyTrashed()->with('creator')->latest()->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar kalender yang terhapus berhasil diambil',
            'data' => $kalenders
        ], 200);
    }

    /**
     * Mengembalikan kegiatan yang sudah di-soft delete.
     * Cek otorisasi dihandle oleh middleware 'permission:kelola_kalender'.
     */
    public function restore($id)
    {
        // findOrFail akan otomatis mengembalikan 404 jika tidak ditemukan
        $kalender = Kalender::onlyTrashed()->findOrFail($id);
        $kalender->restore();

        $kalender->load('creator');

        return response()->json([
            'success' => true,
            'message' => 'Kegiatan kalender berhasil direstore',
            'data' => $kalender
        ], 200);
    }

    /**
     * Menghapus kegiatan secara permanen dari database.
     * Cek otorisasi dihandle oleh middleware 'permission:kelola_kalender'.
     */
    public function forceDelete($id)
    {
        $kalender = Kalender::onlyTrashed()->findOrFail($id);
        $kalender->forceDelete();

        return response()->json([
            'success' => true,
            'message' => 'Kegiatan kalender berhasil dihapus permanen',
        ], 200);
    }
}