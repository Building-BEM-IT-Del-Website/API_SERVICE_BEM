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
        if (Auth::check() && Auth::user()->can('lihat_aspirasi')) {
            // Eager load relasi 'reader' untuk menghindari N+1 problem
            $aspirasis = Aspirasi::with('reader')->latest()->get();
        } else {
            $aspirasis = Aspirasi::whereIn('status', ['In Progress', 'Approved', 'Completed'])
                ->with('reader')
                ->latest()->get();
        }

        return response()->json([
            'success' => true,
            'message' => 'Daftar aspirasi berhasil diambil',

            'data' => $aspirasis
        ], 200);
    }

    /**

     * Store a newly created aspirasi.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'nama' => 'nullable|string|max:255',
        ]);

        $aspirasi = Aspirasi::create($validatedData);

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
        // Muat relasi 'reader' untuk response yang lengkap
        $aspirasi->load('reader');

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
        $validatedData = $request->validate([
            'status' => 'sometimes|required|in:Pending,Approved,Rejected,Completed,In Progress',
            'respon' => 'nullable|string',
        ]);

        // Jika ada status yang diupdate, dicatat siapa yang update
        if ($request->has('status')) {
            $validatedData['read_by'] = Auth::id();
        }

        $aspirasi->update($validatedData);

        // Muat relasi 'reader' agar data terbaru dikirim di response
        $aspirasi->load('reader');
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
        $aspirasi->delete();

        return response()->json([
            'success' => true,
            'message' => 'Aspirasi berhasil dihapus (soft delete)',
        ], 200);
    }

    /**
     * Show trashed aspirasi.
     */
    public function trashed()
    {
        $aspirasis = Aspirasi::onlyTrashed()->with('reader')->latest()->get();


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
        $aspirasi = Aspirasi::onlyTrashed()->findOrFail($id);
        $aspirasi->restore();

        // Muat relasi 'reader' untuk response
        $aspirasi->load('reader');


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
        $aspirasi = Aspirasi::onlyTrashed()->findOrFail($id);
        $aspirasi->forceDelete();

        return response()->json([
            'success' => true,
            'message' => 'Aspirasi berhasil dihapus permanen',
        ], 200);
    }
}
