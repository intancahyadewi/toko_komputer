<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PelangganController extends Controller
{
    public function index()
    {
        return response()->json(Pelanggan::all());
    }

    public function show($id)
    {
        return response()->json(
            Pelanggan::findOrFail($id)
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_pelanggan' => 'required|string|max:10|unique:pelanggan,id_pelanggan|unique:person,id_person',
            'nama' => 'required|string|max:100',
            'no_telepon' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100|unique:pelanggan,email',
            'nama_jalan' => 'nullable|string|max:150',
            'kota' => 'nullable|string|max:50',
            'kode_pos' => 'nullable|string|max:10',
        ]);

        $pelanggan = DB::transaction(function () use ($data, $request) {
            Person::create([
                'id_person' => $data['id_pelanggan'],
                'nama' => $data['nama'],
                'no_telepon' => $data['no_telepon'] ?? null,
            ]);

            return Pelanggan::create([
                'id_pelanggan' => $data['id_pelanggan'],
                'email' => $data['email'] ?? null,
                'nama_jalan' => $data['nama_jalan'] ?? null,
                'kota' => $data['kota'] ?? null,
                'kode_pos' => $data['kode_pos'] ?? null,
                'user_id' => $request->user()->id,
            ]);
        });

        return response()->json([
            'message' => 'Pelanggan berhasil ditambahkan',
            'data' => $pelanggan
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $pelanggan = Pelanggan::findOrFail($id);
        $user = $request->user();

        if ($user->role !== 'admin' &&
            $pelanggan->user_id != $user->id) {
            return response()->json([
                'message' => 'Anda hanya boleh mengubah pelanggan milik sendiri'
            ], 403);
        }

        $data = $request->validate([
            'nama' => 'sometimes|required|string|max:100',
            'no_telepon' => 'sometimes|nullable|string|max:20',
            'email' => [
                'sometimes', 'nullable', 'email', 'max:100',
                Rule::unique('pelanggan', 'email')
                    ->ignore($pelanggan->id_pelanggan, 'id_pelanggan'),
            ],
            'nama_jalan' => 'sometimes|nullable|string|max:150',
            'kota' => 'sometimes|nullable|string|max:50',
            'kode_pos' => 'sometimes|nullable|string|max:10',
        ]);

        DB::transaction(function () use ($pelanggan, $data) {
            $personData = array_intersect_key(
                $data,
                array_flip(['nama', 'no_telepon'])
            );

            if (!empty($personData)) {
                Person::where('id_person', $pelanggan->id_pelanggan)
                    ->update($personData);
            }

            $pelanggan->update(
                array_diff_key($data, array_flip(['nama', 'no_telepon']))
            );
        });

        return response()->json([
            'message' => 'Pelanggan berhasil diperbarui',
            'data' => $pelanggan->fresh()
        ]);
    }

    public function destroy(Request $request, $id)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Akses khusus Admin'
            ], 403);
        }

        $pelanggan = Pelanggan::findOrFail($id);
        $pelanggan->delete();

        return response()->json([
            'message' => 'Pelanggan berhasil dihapus'
        ]);
    }
}
