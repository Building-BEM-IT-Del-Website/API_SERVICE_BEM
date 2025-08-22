<?php

namespace App\Http\Controllers;

use App\Models\JenisOrmawa;
use Illuminate\Http\Request;

class JenisOrmawaController extends Controller
{
    public function index()
    {
        return response()->json(JenisOrmawa::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
        ], [
            'nama.required' => 'Nama jenis Ormawa wajib diisi.',
            'nama.string' => 'Nama harus berupa teks.',
            'nama.max' => 'Nama maksimal 255 karakter.',
        ]);

        $existing = JenisOrmawa::withTrashed()->where('nama', $validated['nama'])->first();
        if ($existing) {
            if ($existing->trashed()) {
                return response()->json([
                    'message' => 'Nama jenis Ormawa ini sudah ada tapi telah dihapus. Anda bisa merestore data ini.',
                    'restore_id' => $existing->id
                ], 422);
            } else {
                return response()->json([
                    'message' => 'Nama jenis Ormawa ini sudah ada dan tidak bisa digunakan lagi.'
                ], 422);
            }
        }

        $jenis = JenisOrmawa::create($validated);

        return response()->json([
            'message' => 'Jenis Ormawa berhasil dibuat.',
            'data' => $jenis
        ], 201);
    }

    public function show($id)
    {
        $jenis = JenisOrmawa::findOrFail($id);
        return response()->json($jenis);
    }

    public function update(Request $request, $id)
    {
        $jenis = JenisOrmawa::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
        ], [
            'nama.required' => 'Nama jenis Ormawa wajib diisi.',
            'nama.string' => 'Nama harus berupa teks.',
            'nama.max' => 'Nama maksimal 255 karakter.',
        ]);

        $existing = JenisOrmawa::withTrashed()
            ->where('nama', $validated['nama'])
            ->where('id', '!=', $id)
            ->first();

        if ($existing) {
            if ($existing->trashed()) {
                return response()->json([
                    'message' => 'Nama jenis Ormawa ini sudah ada tapi telah dihapus. Anda bisa merestore data ini.',
                    'restore_id' => $existing->id
                ], 422);
            } else {
                return response()->json([
                    'message' => 'Nama jenis Ormawa ini sudah ada dan tidak bisa digunakan lagi.'
                ], 422);
            }
        }

        $jenis->update($validated);

        return response()->json([
            'message' => 'Jenis Ormawa berhasil diperbarui.',
            'data' => $jenis
        ]);
    }

    public function destroy($id)
    {
        $jenis = JenisOrmawa::findOrFail($id);
        $jenis->delete();

        return response()->json(['message' => 'Jenis Ormawa berhasil dihapus (soft delete).']);
    }

    public function trashed()
    {
        $trashed = JenisOrmawa::onlyTrashed()->get();
        return response()->json($trashed);
    }

    public function restore($id)
    {
        $jenis = JenisOrmawa::onlyTrashed()->findOrFail($id);
        $jenis->restore();

        return response()->json([
            'message' => 'Jenis Ormawa berhasil direstore.',
            'data' => $jenis
        ]);
    }

    public function forceDelete($id)
    {
        $jenis = JenisOrmawa::onlyTrashed()->findOrFail($id);
        $jenis->forceDelete();

        return response()->json(['message' => 'Jenis Ormawa berhasil dihapus permanen.']);
    }
}
