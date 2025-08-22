<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class JabatanController extends Controller
{
    public function index()
    {
        return response()->json(Jabatan::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'deskripsi' => 'required|string',
            'level' => 'required|integer',
        ], [
            'nama.required' => 'Nama jabatan wajib diisi.',
            'nama.string' => 'Nama jabatan harus berupa teks.',
            'nama.max' => 'Nama jabatan maksimal 100 karakter.',
            'deskripsi.required' => 'Deskripsi wajib diisi.',
            'deskripsi.string' => 'Deskripsi harus berupa teks.',
            'level.required' => 'Level jabatan wajib diisi.',
            'level.integer' => 'Level jabatan harus berupa angka.',
        ]);

        $existing = Jabatan::withTrashed()->where('nama', $validated['nama'])->first();

        if ($existing) {
            if ($existing->trashed()) {
                return response()->json([
                    'message' => 'Nama jabatan ini sudah ada tapi telah dihapus. Anda bisa merestore data ini.',
                    'restore_id' => $existing->id
                ], 422);
            } else {
                return response()->json([
                    'message' => 'Nama jabatan ini sudah ada dan tidak bisa digunakan lagi.'
                ], 422);
            }
        }

        $jabatan = Jabatan::create($validated);

        return response()->json([
            'message' => 'Jabatan berhasil dibuat.',
            'data' => $jabatan
        ], 201);
    }

    public function show(string $id)
    {
        $jabatan = Jabatan::findOrFail($id);
        return response()->json($jabatan);
    }

    public function update(Request $request, string $id)
    {
        $jabatan = Jabatan::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'sometimes|string|max:100',
            'deskripsi' => 'sometimes|string',
            'level' => 'sometimes|integer',
        ], [
            'nama.string' => 'Nama jabatan harus berupa teks.',
            'nama.max' => 'Nama jabatan maksimal 100 karakter.',
            'deskripsi.string' => 'Deskripsi harus berupa teks.',
            'level.integer' => 'Level jabatan harus berupa angka.',
        ]);

        if (isset($validated['nama'])) {
            $existing = Jabatan::withTrashed()
                ->where('nama', $validated['nama'])
                ->where('id', '!=', $id)
                ->first();

            if ($existing) {
                if ($existing->trashed()) {
                    return response()->json([
                        'message' => 'Nama jabatan ini sudah ada tapi telah dihapus. Anda bisa merestore data ini.',
                        'restore_id' => $existing->id
                    ], 422);
                } else {
                    return response()->json([
                        'message' => 'Nama jabatan ini sudah ada dan tidak bisa digunakan lagi.'
                    ], 422);
                }
            }
        }

        $jabatan->update($validated);

        return response()->json([
            'message' => 'Jabatan berhasil diperbarui.',
            'data' => $jabatan
        ]);
    }

    public function destroy(string $id)
    {
        $jabatan = Jabatan::findOrFail($id);
        $jabatan->delete();

        return response()->json(['message' => 'Jabatan berhasil dihapus (soft delete).']);
    }

    public function trashed()
    {
        $trashed = Jabatan::onlyTrashed()->get();
        return response()->json($trashed);
    }

    public function restore(string $id)
    {
        $jabatan = Jabatan::onlyTrashed()->findOrFail($id);
        $jabatan->restore();

        return response()->json([
            'message' => 'Jabatan berhasil direstore.',
            'data' => $jabatan
        ]);
    }

    public function forceDelete(string $id)
    {
        $jabatan = Jabatan::onlyTrashed()->findOrFail($id);
        $jabatan->forceDelete();

        return response()->json(['message' => 'Jabatan berhasil dihapus permanen.']);
    }
}
