<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Perangkat;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PerangkatController extends Controller
{
    // Melihat semua perangkat
    public function index()
    {
        return response()->json(Perangkat::all());
    }

    // Melihat satu perangkat
    public function show($nomor_seri)
    {
        return response()->json(
            Perangkat::findOrFail($nomor_seri)
        );
    }

    // Menambahkan perangkat
    public function store(Request $request)
    {
        $data = $request->validate([
            'nomor_seri' => 'required|string|max:50|unique:perangkat,nomor_seri',
            'merek' => 'nullable|string|max:50',
            'model' => 'nullable|string|max:50',
            'kondisi' => 'nullable|string|max:100',
            'status_kepemilikan' => 'nullable|string|max:50',
        ]);

        $perangkat = Perangkat::create($data);

        return response()->json([
            'message' => 'Perangkat berhasil ditambahkan',
            'data' => $perangkat
        ], 201);
    }

    // Mengubah perangkat
    public function update(Request $request, $nomor_seri)
    {
        $perangkat = Perangkat::findOrFail($nomor_seri);

        $data = $request->validate([
            'merek' => 'sometimes|nullable|string|max:50',
            'model' => 'sometimes|nullable|string|max:50',
            'kondisi' => 'sometimes|nullable|string|max:100',
            'status_kepemilikan' => 'sometimes|nullable|string|max:50',
        ]);

        $perangkat->update($data);

        return response()->json([
            'message' => 'Perangkat berhasil diperbarui',
            'data' => $perangkat
        ]);
    }

    // Menghapus perangkat
    public function destroy($nomor_seri)
    {
        $perangkat = Perangkat::findOrFail($nomor_seri);

        $perangkat->delete();

        return response()->json([
            'message' => 'Perangkat berhasil dihapus'
        ]);
    }
}
