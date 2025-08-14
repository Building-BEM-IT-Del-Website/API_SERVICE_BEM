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
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $jenis = JenisOrmawa::create($request->only('nama'));

        return response()->json($jenis, 201);
    }

    public function show($id)
    {
        $jenis = JenisOrmawa::findOrFail($id);
        return response()->json($jenis);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
        ]);

        $jenis = JenisOrmawa::findOrFail($id);
        $jenis->update($request->only('nama'));

        return response()->json($jenis);
    }

    public function destroy($id)
    {
        $jenis = JenisOrmawa::findOrFail($id);
        $jenis->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }

    // Restore soft deleted
    public function restore($id)
    {
        $jenis = JenisOrmawa::onlyTrashed()->findOrFail($id);
        $jenis->restore();

        return response()->json(['message' => 'Restored successfully']);
    }

    // Force delete permanently
    public function forceDelete($id)
    {
        $jenis = JenisOrmawa::onlyTrashed()->findOrFail($id);
        $jenis->forceDelete();

        return response()->json(['message' => 'Deleted permanently']);
    }

    // Get all soft-deleted entries
    public function trashed()
    {
        return response()->json(JenisOrmawa::onlyTrashed()->get());
    }
}
