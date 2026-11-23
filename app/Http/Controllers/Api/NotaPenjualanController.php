<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NotaPenjualan;
use Illuminate\Http\Request;

class NotaPenjualanController extends Controller
{
    // Melihat semua nota
    public function index()
    {
        return response()->json(NotaPenjualan::all());
    }

    // Melihat detail nota
    public function show($nomor_nota)
    {
        return response()->json(
            NotaPenjualan::findOrFail($nomor_nota)
        );
    }

    // Admin dan User membuat nota
    public function store(Request $request)
    {
        $data = $request->validate([
            'nomor_nota' => 'required|string|max:20|unique:nota_penjualan,nomor_nota',
            'tanggal_transaksi' => 'nullable|date',
            'metode_pembayaran' => 'nullable|string|max:30',
            'total_biaya' => 'nullable|numeric|min:0',
            'id_staf' => 'required|string|exists:staf_penjualan,id_staf',
            'id_pelanggan' => 'required|string|exists:pelanggan,id_pelanggan',
            'nomor_seri' => 'required|string|exists:perangkat,nomor_seri',
        ]);

        // Pemilik otomatis berdasarkan akun login
        $data['user_id'] = $request->user()->id;

        $nota = NotaPenjualan::create($data);

        return response()->json([
            'message' => 'Nota penjualan berhasil ditambahkan',
            'data' => $nota
        ], 201);
    }

    // Admin mengubah semua nota, User hanya miliknya
    public function update(Request $request, $nomor_nota)
    {
        $nota = NotaPenjualan::findOrFail($nomor_nota);
        $user = $request->user();

        if ($user->role !== 'admin' &&
            $nota->user_id != $user->id) {

            return response()->json([
                'message' => 'Anda hanya boleh mengubah nota milik sendiri'
            ], 403);
        }

        $data = $request->validate([
            'tanggal_transaksi' => 'sometimes|nullable|date',
            'metode_pembayaran' => 'sometimes|nullable|string|max:30',
            'total_biaya' => 'sometimes|nullable|numeric|min:0',
            'id_staf' => 'sometimes|required|string|exists:staf_penjualan,id_staf',
            'id_pelanggan' => 'sometimes|required|string|exists:pelanggan,id_pelanggan',
            'nomor_seri' => 'sometimes|required|string|exists:perangkat,nomor_seri',
        ]);

        $nota->update($data);

        return response()->json([
            'message' => 'Nota penjualan berhasil diperbarui',
            'data' => $nota
        ]);
    }

    // Hanya Admin boleh menghapus nota
    public function destroy(Request $request, $nomor_nota)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Akses khusus Admin'
            ], 403);
        }

        $nota = NotaPenjualan::findOrFail($nomor_nota);

        $nota->delete();

        return response()->json([
            'message' => 'Nota penjualan berhasil dihapus'
        ]);
    }
}
