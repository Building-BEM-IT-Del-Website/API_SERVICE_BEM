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
        'nama' => 'required|string|max:255',
    ]);

    $berita = Berita::create($request->only('nama'));

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
            'nama' => 'required|string|max:255'
        ]);

        $berita->update([
            'nama' => $request->nama
        ]);

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
