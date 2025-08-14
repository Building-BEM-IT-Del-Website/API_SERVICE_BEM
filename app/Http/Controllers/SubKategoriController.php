<?php

namespace App\Http\Controllers;

use App\Models\SubKategori;
use Illuminate\Http\Request;

class SubKategoriController extends Controller
{
        public function index()
    {
        return SubKategori::all();
    }

   public function store(Request $request)
{
    $validated = $request->validate([
        'nama_sub_kategori' => 'required|string|max:255',
    ]);

    $validated['create_by'] = auth()->id(); // ambil user login

    $subKategori = SubKategori::create($validated);

    return response()->json($subKategori, 201);
}
    public function show($id)
    {
        $subKategori = SubKategori::findOrFail($id);
        return response()->json($subKategori);
    }

    public function update(Request $request, $id)
    {
        $subKategori = SubKategori::findOrFail($id);

        $validated = $request->validate([
            'nama_sub_kategori' => 'required|string|max:255',
            'create_by' => 'nullable|exists:users,id',
        ]);

        $subKategori->update($validated);

        return response()->json($subKategori);
    }

    public function destroy($id)
    {
        $subKategori = SubKategori::findOrFail($id);
        $subKategori->delete();

        return response()->json(['message' => 'Sub kategori soft deleted.']);
    }

    public function trashed()
    {
        return SubKategori::onlyTrashed()->get();
    }

    public function restore($id)
    {
        $subKategori = SubKategori::onlyTrashed()->findOrFail($id);
        $subKategori->restore();

        return response()->json(['message' => 'Sub kategori restored.']);
    }

    public function forceDelete($id)
    {
        $subKategori = SubKategori::onlyTrashed()->findOrFail($id);
        $subKategori->forceDelete();

        return response()->json(['message' => 'Sub kategori permanently deleted.']);
    }
}
