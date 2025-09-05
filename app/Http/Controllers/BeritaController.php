<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;

class BeritaController extends Controller
{
    public function index()
    {
        $data = Berita::all();
        return response()->json([
            'success' => true,
            'message' => 'List semua berita',
            'data' => $data
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'            => 'required|string|max:255',
            'deskripsi'       => 'nullable|string',
            'file_paths'      => 'nullable|json',
            'tipe_berita'     => 'required|in:Info,Pengumuman,Event,Umum,Darurat,Lainnya',
            'tanggal_mulai'   => 'nullable|date',
            'tanggal_berakhir'=> 'nullable|date',
            'create_by'       => 'nullable|exists:users,id',
            'user_id'         => 'nullable|exists:users,id',
            'ormawa_id'       => 'nullable|exists:ormawas,id',
        ]);

        $berita = Berita::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Berita berhasil ditambahkan',
            'data' => $berita,
        ], 201);
    }

    public function show($id)
    {
        $berita = Berita::find($id);

        if (!$berita) {
            return response()->json([
                'success' => false,
                'message' => 'Berita tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Detail berita',
            'data' => $berita
        ]);
    }

    public function update(Request $request, $id)
    {
        $berita = Berita::find($id);

        if (!$berita) {
            return response()->json([
                'success' => false,
                'message' => 'Berita tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'nama'            => 'sometimes|required|string|max:255',
            'deskripsi'       => 'nullable|string',
            'file_paths'      => 'nullable|json',
            'tipe_berita'     => 'nullable|in:Info,Pengumuman,Event,Umum,Darurat,Lainnya',
            'tanggal_mulai'   => 'nullable|date',
            'tanggal_berakhir'=> 'nullable|date',
            'create_by'       => 'nullable|exists:users,id',
            'user_id'         => 'nullable|exists:users,id',
            'ormawa_id'       => 'nullable|exists:ormawas,id',
        ]);

        $berita->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Berita berhasil diperbarui',
            'data' => $berita
        ]);
    }

    public function destroy($id)
    {
        $berita = Berita::find($id);

        if (!$berita) {
            return response()->json([
                'success' => false,
                'message' => 'Berita tidak ditemukan'
            ], 404);
        }

        $berita->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berita berhasil dihapus'
        ]);
    }
}
