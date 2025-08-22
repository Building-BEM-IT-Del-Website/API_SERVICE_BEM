<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrmawaResource;
use App\Models\Ormawa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrmawaController extends Controller
{
    public function index()
    {
        $ormawas = Ormawa::with('jenisOrmawa')->get();

        return response()->json([
            'message' => 'Data Ormawa berhasil diambil',
            'data' => OrmawaResource::collection($ormawas)
        ]);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'jenis_ormawa_id' => 'required|exists:jenis_ormawas,id',
            'deskripsi' => 'required|string',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'visi' => 'required|string',
            'misi' => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);

        $existing = Ormawa::withTrashed()->where('nama', $validated['nama'])->first();
        if ($existing) {
            if ($existing->trashed()) {
                return response()->json([
                    'message' => 'Nama Ormawa ini sudah ada tapi telah dihapus. Anda bisa merestore data ini.',
                    'restore_id' => $existing->id
                ], 422);
            } else {
                return response()->json([
                    'message' => 'Nama Ormawa ini sudah ada dan tidak bisa digunakan lagi.'
                ], 422);
            }
        }

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $path = $file->store('ormawas', 'public');
            $validated['logo'] = $path;
        }

        $ormawa = Ormawa::create($validated);
        $ormawa->logo = $ormawa->logo ? asset('storage/' . $ormawa->logo) : null;

        return response()->json([
            'message' => 'Ormawa berhasil dibuat.',
            'data' => $ormawa
        ], 201);
    }

    public function show($id)
    {
        $ormawa = Ormawa::findOrFail($id);
        if ($ormawa->logo) {
            $ormawa->logo = asset('storage/' . $ormawa->logo);
        }
        return response()->json([
            'message' => 'Detail Ormawa berhasil diambil.',
            'data' => $ormawa
        ]);
    }

    public function update(Request $request, $id)
    {
        $ormawa = Ormawa::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'sometimes|required|string|max:100',
            'jenis_ormawa_id' => 'sometimes|required|exists:jenis_ormawas,id',
            'deskripsi' => 'sometimes|required|string',
            'logo' => 'sometimes|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'visi' => 'sometimes|required|string',
            'misi' => 'sometimes|required|string',
            'status' => 'sometimes|required|in:active,inactive',
        ]);

        if (isset($validated['nama'])) {
            $existing = Ormawa::withTrashed()
                ->where('nama', $validated['nama'])
                ->where('id', '!=', $id)
                ->first();
            if ($existing) {
                if ($existing->trashed()) {
                    return response()->json([
                        'message' => 'Nama Ormawa ini sudah ada tapi telah dihapus. Anda bisa merestore data ini.',
                        'restore_id' => $existing->id
                    ], 422);
                } else {
                    return response()->json([
                        'message' => 'Nama Ormawa ini sudah ada dan tidak bisa digunakan lagi.'
                    ], 422);
                }
            }
        }

        if ($request->hasFile('logo')) {
            if ($ormawa->logo && Storage::disk('public')->exists($ormawa->logo)) {
                Storage::disk('public')->delete($ormawa->logo);
            }
            $file = $request->file('logo');
            $path = $file->store('ormawas', 'public');
            $validated['logo'] = $path;
        }

        $ormawa->update($validated);
        if ($ormawa->logo) {
            $ormawa->logo = asset('storage/' . $ormawa->logo);
        }

        return response()->json([
            'message' => 'Ormawa berhasil diperbarui.',
            'data' => $ormawa
        ]);
    }

    public function destroy($id)
    {
        $ormawa = Ormawa::findOrFail($id);
        $ormawa->delete();

        return response()->json(['message' => 'Ormawa berhasil dihapus (soft delete).']);
    }

    public function trashed()
    {
        $trashed = Ormawa::onlyTrashed()->with('jenisOrmawa')->get();

        return response()->json([
            'message' => 'Data Ormawa terhapus berhasil diambil.',
            'data' => OrmawaResource::collection($trashed)
        ]);
    }
    public function restore($id)
    {
        $ormawa = Ormawa::onlyTrashed()->findOrFail($id);
        $ormawa->restore();
        if ($ormawa->logo) {
            $ormawa->logo = asset('storage/' . $ormawa->logo);
        }

        return response()->json([
            'message' => 'Ormawa berhasil direstore.',
            'data' => $ormawa
        ]);
    }

    public function forceDelete($id)
    {
        $ormawa = Ormawa::onlyTrashed()->findOrFail($id);
        if ($ormawa->logo && Storage::disk('public')->exists($ormawa->logo)) {
            Storage::disk('public')->delete($ormawa->logo);
        }
        $ormawa->forceDelete();

        return response()->json(['message' => 'Ormawa berhasil dihapus permanen.']);
    }
}
