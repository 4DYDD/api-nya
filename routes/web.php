<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api/tes', function () {
    return response()->json(
        [
            [
                "message" => "halo dunia",
                "title" => "Selamat Malam Dunia",
                "description" => "Lorem ipsum dolor sit amet
                                  consectetur adipisicing elit. Provident, doloribus."
            ],
            [
                "message" => "halo Oemat Manoesia",
                "title" => "Selamat Malam Oemat Manoesia",
                "description" => "Lorem ipsum dolor sit amet
                                  consectetur adipisicing elit. doloribus, Provident."
            ],
        ]
    );
});

Route::get('/api/csrf-token', function () {
    return response()->json(['csrf_token' => csrf_token()]);
});

Route::get('/api/users', function () {
    $users = User::all();
    return response()->json($users);
});

Route::post('/api/users', function (Request $request) {
    try {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            // tambahkan aturan validasi lainnya sesuai kebutuhan
        ]);

        // Jika validasi berhasil, $validatedData berisi data yang valid
        // Anda dapat menggunakan $validatedData untuk membuat atau memperbarui data
        // Contoh:
        $user = new User;
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->password = bcrypt($validatedData['password']);
        $user->save();

        return response()->json(['message' => 'User created successfully'], 201);
    } catch (\Illuminate\Validation\ValidationException $e) {
        // Jika validasi gagal, tangkap exception dan kirim respons error
        return response()->json(['errors' => $e->errors()], 422);
    }
});
