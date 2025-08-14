<?php

namespace App\Http\Controllers;

use App\Http\Resources\StrukturOrganisasiResource;
use App\Models\StrukturOrganisasi;
use Illuminate\Http\Request;

class StrukturOrganisasiController extends Controller
{
    public function index()
    {
        $data = StrukturOrganisasi::latest()->get();
        return StrukturOrganisasiResource::collection($data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'ormawa_id' => 'required|exists:ormawas,id',
            'jabatan_id' => 'required|exists:jabatan,id',
            'tanggal_mulai' => 'nullable|date',
            'status' => 'required|in:active,nonactive'
        ]);

        $struktur = StrukturOrganisasi::create([
            ...$validated,
            'tanggal_selesai' => null, // selalu null saat dibuat
        ]);

        return new StrukturOrganisasiResource($struktur);
    }

    public function show($id)
    {
        $struktur = StrukturOrganisasi::findOrFail($id);
        return new StrukturOrganisasiResource($struktur);
    }

    public function update(Request $request, $id)
    {
        $struktur = StrukturOrganisasi::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'sometimes|required|exists:users,id',
            'ormawa_id' => 'sometimes|required|exists:ormawas,id',
            'jabatan_id' => 'sometimes|required|exists:jabatan,id',
            'tanggal_mulai' => 'nullable|date',
            'status' => 'sometimes|required|in:active,nonactive'
        ]);

        $struktur->fill($validated);

        if (
            $struktur->isDirty('status')
        ) {
            if ($struktur->status === 'nonactive' && empty($struktur->tanggal_selesai)) {
                $struktur->tanggal_selesai = now();
            } elseif ($struktur->status === 'active') {
                $struktur->tanggal_selesai = null;
            }
        }


        $struktur->save();

        return new StrukturOrganisasiResource($struktur);
    }

    public function destroy($id)
    {
        $struktur = StrukturOrganisasi::findOrFail($id);
        $struktur->delete();

        return response()->json(['message' => 'Struktur organisasi berhasil dihapus (soft delete).']);
    }

    public function trashed()
    {
        $trashed = StrukturOrganisasi::onlyTrashed()->get();
        return StrukturOrganisasiResource::collection($trashed);
    }

    public function restore($id)
    {
        $struktur = StrukturOrganisasi::onlyTrashed()->findOrFail($id);
        $struktur->restore();

        return new StrukturOrganisasiResource($struktur);
    }

    public function forceDelete($id)
    {
        $struktur = StrukturOrganisasi::onlyTrashed()->findOrFail($id);
        $struktur->forceDelete();

        return response()->json(['message' => 'Struktur organisasi dihapus permanen.']);
    }
}
