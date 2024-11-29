<?php

namespace App\Http\Controllers;

use App\Models\UserReseller;
use Illuminate\Http\Request;

class UserResellerController extends Controller
{
    public function index()
    {
        $resellers = UserReseller::all(); // Mengambil semua reseller
        return response()->json($resellers);
    }

    public function store(Request $request)
    {
        // Validasi data, kode_reseller tidak perlu divalidasi karena otomatis dibuat
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users_reseller,email',
            'address' => 'required',
            'phone_number' => 'required',
            'latitude' => 'required|numeric', // Tambahkan validasi latitude
            'longitude' => 'nullable|numeric', // Jika longitude opsional
        ]);        

        // Menghitung jumlah reseller yang sudah ada
        $lastReseller = UserReseller::orderBy('id', 'desc')->first();

        // Menghasilkan kode reseller baru berdasarkan jumlah reseller yang ada
        $nextNumber = $lastReseller ? ((int)substr($lastReseller->kode_reseller, 2)) + 1 : 1; 
        $kode_reseller = 'RS' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT); 

        // Tambahkan kode_reseller ke dalam request data
        $resellerData = $request->all();
        $resellerData['kode_reseller'] = $kode_reseller;

        // Simpan reseller baru
        $reseller = UserReseller::create($resellerData);

        return response()->json([
            'message' => 'Reseller berhasil ditambahkan.',
            'reseller' => $reseller
        ], 201);
    }

    public function show($id)
    {
        $reseller = UserReseller::findOrFail($id); // Mengambil reseller berdasarkan ID
        return response()->json($reseller);
    }

    public function update(Request $request, $id)
    {
        $reseller = UserReseller::findOrFail($id); // Mengambil reseller berdasarkan ID

        $request->validate([
            'kode_reseller' => 'required',
            'name' => 'required',
            'email' => 'required|email|unique:users_reseller,email,' . $reseller->id,
            'address' => 'required',
            'phone_number' => 'required',
        ]);

        $reseller->update($request->all());

        return response()->json([
            'message' => 'Reseller berhasil diperbarui.',
            'reseller' => $reseller
        ], 200);
    }

    public function destroy($id)
    {
        $reseller = UserReseller::findOrFail($id); // Mengambil reseller berdasarkan ID
        $reseller->delete();

        return response()->json([
            'message' => 'Reseller berhasil dihapus.'
        ], 200);
    }
}