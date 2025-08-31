<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Aspirasi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Pengumuman; // Pastikan model ini ada dan namespace-nya benar

class BerandaController extends Controller
{
    /**
     * Pengumuman dan Agenda
     */
    public function latest()
    {
        $pengumuman = Pengumuman::latest()->take(3)->get();
        return response()->json(['data' => $pengumuman]);
    }

    public function upcomingEvents()
    {
        $agenda = Pengumuman::where('tanggal_mulai', '>=', now())
            ->orderBy('tanggal_mulai', 'asc')
            ->take(3)
            ->get();
        return response()->json(['data' => $agenda]);
    }

    /**
     * Aspirasi
     */
    public function getPublished()
    {
        $aspirasi = Aspirasi::whereNotIn('status', ['Pending', 'Rejected'])
            ->latest()
            ->take(10)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar aspirasi berhasil diambil.',
            'data' => $aspirasi,
        ]);
    }

    public function storeAspirasi(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'nama' => 'nullable|string|max:255',
        ]);

        Aspirasi::create([
            'judul' => $request->judul,
            'deskripsi' => $request->deskripsi,
            'nama' => $request->nama,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Aspirasi Anda telah berhasil dikirim. Terima kasih!',
        ], 201);
    }
}