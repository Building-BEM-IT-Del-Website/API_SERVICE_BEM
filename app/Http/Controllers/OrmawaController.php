<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrmawaResource;
use App\Models\Ormawa;
use Illuminate\Http\Request;

class OrmawaController extends Controller
{
    public function index()
    {
        return OrmawaResource::collection(Ormawa::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'jenis_ormawa_id' => 'required|exists:jenis_ormawas,id',
            'deskripsi' => 'required|string',
            'logo' => 'required|string',
            'visi' => 'required|string',
            'misi' => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);

        $ormawa = Ormawa::create($validated);

        return new OrmawaResource($ormawa);
    }

    public function show($id)
    {
        $ormawa = Ormawa::findOrFail($id);
        return new OrmawaResource($ormawa);
    }

    public function update(Request $request, $id)
    {
        $ormawa = Ormawa::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'sometimes|required|string|max:100',
            'jenis_ormawa_id' => 'sometimes|required|exists:jenis_ormawas,id',
            'deskripsi' => 'sometimes|required|string',
            'logo' => 'sometimes|required|string',
            'visi' => 'sometimes|required|string',
            'misi' => 'sometimes|required|string',
            'status' => 'sometimes|required|in:active,inactive',
        ]);

        $ormawa->update($validated);

        return new OrmawaResource($ormawa);
    }

    public function destroy($id)
    {
        $ormawa = Ormawa::findOrFail($id);
        $ormawa->delete();

        return response()->json(['message' => 'Ormawa berhasil dihapus (soft delete).']);
    }

    public function trashed()
    {
        $trashed = Ormawa::onlyTrashed()->get();
        return OrmawaResource::collection($trashed);
    }

    public function restore($id)
    {
        $ormawa = Ormawa::onlyTrashed()->findOrFail($id);
        $ormawa->restore();

        return new OrmawaResource($ormawa);
    }

    public function forceDelete($id)
    {
        $ormawa = Ormawa::onlyTrashed()->findOrFail($id);
        $ormawa->forceDelete();

        return response()->json(['message' => 'Ormawa berhasil dihapus permanen.']);
    }
}
