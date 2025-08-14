<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;

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
        ]);

        $jabatan = Jabatan::create($validated);

        return response()->json($jabatan, 201);
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
        ]);

        $jabatan->update($validated);

        return response()->json($jabatan);
    }

    public function destroy(string $id)
    {
        $jabatan = Jabatan::findOrFail($id);
        $jabatan->delete();

        return response()->json(['message' => 'Jabatan deleted']);
    }
}
