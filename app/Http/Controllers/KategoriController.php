<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    public function index()
    {
        try {
            $data = Kategori::all();
            return response()->json([
                'success' => true,
                'message' => 'Daftar semua kategori',
                'data' => $data
            ]);
        } catch (Exception $e) {
            return $this->internalError($e);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sub_kategoris_id' => 'required|exists:sub_kategoris,id',
            'nama' => 'required|unique:kategoris,nama',
            'deskripsi' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator);
        }

        try {
            $data = $validator->validated();
            $data['create_by'] = auth()->id();

            $kategori = Kategori::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil ditambahkan',
                'data' => $kategori
            ], 201);
        } catch (Exception $e) {
            return $this->internalError($e);
        }
    }

    public function show($id)
    {
        try {
            $kategori = Kategori::findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Detail kategori',
                'data' => $kategori
            ]);
        } catch (ModelNotFoundException $e) {
            return $this->notFound('Kategori tidak ditemukan');
        } catch (Exception $e) {
            return $this->internalError($e);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'sub_kategoris_id' => 'required|exists:sub_kategoris,id',
            'nama' => 'required|unique:kategoris,nama,' . $id,
            'deskripsi' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->validationError($validator);
        }

        try {
            $kategori = Kategori::findOrFail($id);
            $kategori->update($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil diperbarui',
                'data' => $kategori
            ]);
        } catch (ModelNotFoundException $e) {
            return $this->notFound('Kategori tidak ditemukan');
        } catch (Exception $e) {
            return $this->internalError($e);
        }
    }

    public function destroy($id)
    {
        try {
            $kategori = Kategori::findOrFail($id);
            $kategori->delete();

            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil dihapus (soft delete)'
            ]);
        } catch (ModelNotFoundException $e) {
            return $this->notFound('Kategori tidak ditemukan');
        } catch (Exception $e) {
            return $this->internalError($e);
        }
    }

    public function trashed()
    {
        try {
            $data = Kategori::onlyTrashed()->get();

            return response()->json([
                'success' => true,
                'message' => 'Daftar kategori yang terhapus',
                'data' => $data
            ]);
        } catch (Exception $e) {
            return $this->internalError($e);
        }
    }

    public function restore($id)
    {
        try {
            $kategori = Kategori::onlyTrashed()->findOrFail($id);
            $kategori->restore();

            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil direstore'
            ]);
        } catch (ModelNotFoundException $e) {
            return $this->notFound('Data tidak ditemukan di trash');
        } catch (Exception $e) {
            return $this->internalError($e);
        }
    }

    public function forceDelete($id)
    {
        try {
            $kategori = Kategori::onlyTrashed()->findOrFail($id);
            $kategori->forceDelete();

            return response()->json([
                'success' => true,
                'message' => 'Kategori berhasil dihapus permanen'
            ]);
        } catch (ModelNotFoundException $e) {
            return $this->notFound('Data tidak ditemukan di trash');
        } catch (Exception $e) {
            return $this->internalError($e);
        }
    }

    // 🔽 Helper untuk validasi gagal
    protected function validationError($validator)
    {
        return response()->json([
            'success' => false,
            'message' => 'Validasi gagal',
            'errors' => $validator->errors()
        ], 422);
    }

    // 🔽 Helper untuk not found
    protected function notFound($message)
    {
        return response()->json([
            'success' => false,
            'message' => $message
        ], 404);
    }

    // 🔽 Helper untuk internal error
    protected function internalError($e)
    {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan pada server',
            'error' => $e->getMessage()
        ], 500);
}

}
