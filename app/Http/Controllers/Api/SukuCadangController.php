<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SukuCadang;
use Illuminate\Http\Request;

class SukuCadangController extends Controller
{
    // Admin dan User melihat semua suku cadang
    public function index()
    {
        return response()->json(SukuCadang::all());
    }

    // Admin dan User melihat satu suku cadang
    public function show($kode)
    {
        return response()->json(
            SukuCadang::findOrFail($kode)
        );
    }

    // Admin dan User menambahkan suku cadang
    public function store(Request $request)
    {
        $data = $request->validate([
            'kode_suku_cadang' => 'required|string|max:20|unique:suku_cadang,kode_suku_cadang',
            'nama_suku_cadang' => 'required|string|max:100',
            'kategori' => 'nullable|string|max:50',
            'harga_satuan' => 'nullable|numeric|min:0',
            'stok_tersedia' => 'nullable|integer|min:0',
        ]);

        // Pemilik diambil dari akun yang login
        $data['user_id'] = $request->user()->id;

        $sukuCadang = SukuCadang::create($data);

        return response()->json([
            'message' => 'Suku cadang berhasil ditambahkan',
            'data' => $sukuCadang
        ], 201);
    }

    // Admin bebas update, User hanya data miliknya
    public function update(Request $request, $kode)
    {
        $sukuCadang = SukuCadang::findOrFail($kode);
        $user = $request->user();

        if ($user->role !== 'admin' &&
            $sukuCadang->user_id != $user->id) {

            return response()->json([
                'message' => 'Anda hanya boleh mengubah suku cadang milik sendiri'
            ], 403);
        }

        $data = $request->validate([
            'nama_suku_cadang' => 'sometimes|required|string|max:100',
            'kategori' => 'sometimes|nullable|string|max:50',
            'harga_satuan' => 'sometimes|nullable|numeric|min:0',
            'stok_tersedia' => 'sometimes|nullable|integer|min:0',
        ]);

        $sukuCadang->update($data);

        return response()->json([
            'message' => 'Suku cadang berhasil diperbarui',
            'data' => $sukuCadang
        ]);
    }

    // Hanya Admin dapat menghapus
    public function destroy(Request $request, $kode)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Akses khusus Admin'
            ], 403);
        }

        $sukuCadang = SukuCadang::findOrFail($kode);

        $sukuCadang->delete();

        return response()->json([
            'message' => 'Suku cadang berhasil dihapus'
        ]);
    }
}
