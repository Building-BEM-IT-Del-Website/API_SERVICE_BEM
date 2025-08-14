<?php

namespace App\Http\Controllers;

use App\Models\Pengumuman;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
     public function index()
    {
        return response()->json(Pengumuman::all(), 200);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_pengumuman' => 'required|string|unique:pengumuman',
            'deskripsi' => 'nullable|string',
            'kategoris_id' => 'nullable|exists:kategoris,id',
            'file_paths' => 'nullable|array',
            'tipe_pengumuman' => 'in:Reminder,Info,Penting,Umum,Darurat,Lainnya',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_berakhir' => 'nullable|date',
        ]);

        $data['create_by'] = auth()->id();

        $pengumuman = Pengumuman::create($data);
        return response()->json($pengumuman, 201);
    }

    public function show($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        return response()->json($pengumuman, 200);
    }

    public function update(Request $request, $id)
    {
        $pengumuman = Pengumuman::findOrFail($id);

        $data = $request->validate([
            'nama_pengumuman' => 'required|string|unique:pengumuman,nama_pengumuman,' . $id,
            'deskripsi' => 'nullable|string',
            'kategoris_id' => 'nullable|exists:kategoris,id',
            'file_paths' => 'nullable|array',
            'tipe_pengumuman' => 'in:Reminder,Info,Penting,Umum,Darurat,Lainnya',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_berakhir' => 'nullable|date',
        ]);

        $pengumuman->update($data);
        return response()->json($pengumuman, 200);
    }

    public function destroy($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $pengumuman->delete();
        return response()->json(['message' => 'Pengumuman berhasil dihapus'], 200);
    }

    public function trashed()
    {
        return response()->json(Pengumuman::onlyTrashed()->get(), 200);
    }

    public function restore($id)
    {
        $pengumuman = Pengumuman::onlyTrashed()->findOrFail($id);
        $pengumuman->restore();
        return response()->json(['message' => 'Pengumuman berhasil direstore'], 200);
    }

    public function forceDelete($id)
    {
        $pengumuman = Pengumuman::onlyTrashed()->findOrFail($id);
        $pengumuman->forceDelete();
        return response()->json(['message' => 'Pengumuman dihapus permanen'], 200);
    }
}
