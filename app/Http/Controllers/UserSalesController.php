<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserSales;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str; // Import Str class

class UserSalesController extends Controller
{
    public function index()
    {
        return response()->json(UserSales::all(), 200); // Return all users with a 200 OK response
    }

    // Register pengguna baru
    public function create(Request $request)
    {
        // Validasi input
       // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users_sales',
            'password' => 'required|string|min:8',
            'merk_hp' => 'required|string',
            'address' => 'required|string',
            'phone_number' => 'required|string',
            'tanggal_lahir' => 'required|date', // validasi untuk tanggal lahir
            'gender' => 'required|in:L,P', // validasi untuk gender (L = Laki-laki, P = Perempuan)
            'status' => 'sometimes|in:0,1'
        ]);


        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Buat pengguna baru di tabel users_sales
        // Buat pengguna baru di tabel users_sales
        $user = UserSales::create([
            'kode_sales' => $this->generateKodeSales(),  // Kode sales otomatis
            'kode_unik' => $this->generateKodeUnik(),    // Kode unik otomatis
            'merk_hp' => $request->merk_hp,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'address' => $request->address,
            'phone_number' => $request->phone_number,
            'tanggal_lahir' => $request->tanggal_lahir, // Menyimpan tanggal lahir
            'gender' => $request->gender,               // Menyimpan gender
            'status' => $request->status ?? 0, // Status default tidak aktif (0)
        ]);


        // Return response
        return response()->json([
            'message' => 'User registered successfully!',
            'user' => $user,
        ], 201);
    }

    // Login pengguna
    public function login(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Cek apakah email dan password cocok
        $user = UserSales::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Buat token untuk user (gunakan passport atau sanctum)
        $token = $user->createToken('auth_token')->plainTextToken;

        // Return token dan user info
        return response()->json([
            'message' => 'Login successful!',
            'token' => $token,
            'user' => $user,
        ], 200);
    }

    // Read pengguna berdasarkan ID
    public function show($id)
    {
        $user = UserSales::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user, 200);
    }

    // Update pengguna berdasarkan ID
    public function update(Request $request, $id)
    {
        $user = UserSales::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users_sales,email,' . $id,
            'password' => 'sometimes|required|string|min:8',
            'merk_hp' => 'sometimes|required|string',
            'address' => 'sometimes|required|string',
            'phone_number' => 'sometimes|required|string',
            'status' => 'sometimes|in:0,1' // Validasi status hanya 0 atau 1
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Update user data
        if ($request->has('name')) $user->name = $request->name;
        if ($request->has('email')) $user->email = $request->email;
        if ($request->has('password')) $user->password = Hash::make($request->password);
        if ($request->has('merk_hp')) $user->merk_hp = $request->merk_hp;
        if ($request->has('address')) $user->address = $request->address;
        if ($request->has('phone_number')) $user->phone_number = $request->phone_number;
        if ($request->has('status')) $user->status = $request->status; // Update status (0 atau 1)

        $user->save();

        return response()->json([
            'message' => 'User updated successfully!',
            'user' => $user,
        ], 200);
    }

    // Delete pengguna berdasarkan ID
    public function destroy($id)
    {
        $user = UserSales::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully!'], 200);
    }

    // Fungsi tambahan untuk generate kode sales otomatis
    private function generateKodeSales()
    {
        // Mendapatkan kode sales terakhir yang ada di database
        $lastUser = UserSales::orderBy('kode_sales', 'desc')->first();

        // Jika tidak ada pengguna, mulai dari SL000001
        if (!$lastUser) {
            return 'SL000001';
        }

        // Memisahkan prefix 'SL' dan angka
        $lastKodeSales = $lastUser->kode_sales;
        $lastNumber = intval(substr($lastKodeSales, 2)); // Ambil angka setelah 'SL'

        // Increment angkanya
        $newNumber = $lastNumber + 1;

        // Membuat kode sales baru dengan leading zeros (contoh: SL000001)
        return 'SL' . str_pad($newNumber, 6, '0', STR_PAD_LEFT);
    }

    // Fungsi tambahan untuk generate kode unik otomatis
    private function generateKodeUnik()
    {
        // Menggabungkan timestamp dan 4 karakter acak untuk kode unik
        return 'SL' . time() . Str::random(4); // Hasil: KU1695833450ABCD
    }
}
