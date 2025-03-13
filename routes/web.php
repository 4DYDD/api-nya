<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// class generateToken
// {
//     public static $token;

//     public function __construct()
//     {
//         self::$token = response()->json(['csrf_token' => csrf_token()]);
//     }
//     public static function getToken()
//     {
//         return self::$token;
//     }
// }

// new generateToken();





Route::get('/', function () {
    return view('welcome');
});

Route::get('/api/tes', function () {
    return response()->json(
        [
            [
                "message" => "Halo Dunia",
                "title" => "Selamat Malam Dunia",
                "description" => "Lorem ipsum dolor sit amet.
                                  consectetur adipisicing elit. Provident, doloribus.
                                  Minus possimus ducimus libero eveniet delectus quis?"
            ],
            [
                "message" => "Halo Oemat Manoesia",
                "title" => "Selamat Malam Oemat Manoesia",
                "description" => "Lorem ipsum dolor sit amet
                                  consectetur adipisicing elit. doloribus, Provident."
            ],
        ]
    );
});

Route::get('/api/csrf-token', function () {
    $user = User::where("name", "admin")->first();

    return response()->json(["token" => $user->token]);
});

Route::get('/api/users', function () {
    $users = User::where("name", "!=", "admin")->get();
    return response()->json($users);
});

Route::post('/api/users', function (Request $request) {
    try {
        $token = $request->header('X-API-TOKEN');
        $admin = User::where('token', $token)->first();

        if (!$admin) {
            return response()->json(['message' => 'Bukan Admin'], 403);
        }

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

        return response()->json(['message' => 'User created successfully'], 200);
    } catch (\Illuminate\Validation\ValidationException $e) {
        // Jika validasi gagal, tangkap exception dan kirim respons error
        return response()->json(['errors' => $e->errors()], 422);
    }
});


Route::put('/api/users/{user:id}', function (Request $request, User $user) {
    try {
        $token = $request->header('X-API-TOKEN');
        $admin = User::where('token', $token)->first();

        if (!$admin) {
            return response()->json(['message' => 'Bukan Admin'], 403);
        }

        // Validasi data yang masuk
        $validatedData = $request->validate([
            'name' => 'sometimes|nullable|string|max:255',
            'email' => 'sometimes|nullable|email|unique:users,email,' . $user->id, // Abaikan email saat ini
            'password' => 'sometimes|nullable|min:8',
        ]);

        // Update data pengguna
        if (isset($validatedData['name'])) {
            $user->name = $validatedData['name'];
        }

        if (isset($validatedData['email'])) {
            $user->email = $validatedData['email'];
        }

        if (isset($validatedData['password'])) {
            $user->password = Hash::make($validatedData['password']);
        }

        $user->save();

        return response()->json(['message' => 'User updated successfully'], 200);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json(['errors' => $e->errors()], 422);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Failed to update user', 'error' => $e->getMessage()], 500);
    }
});


Route::delete('/api/users/{user:id}', function (Request $request, User $user) {
    try {
        $token = $request->header('X-API-TOKEN');
        $admin = User::where('token', $token)->first();

        if (!$admin) {
            return response()->json(['message' => 'Bukan Admin'], 403); // Ganti 404 dengan 403 (Forbidden)
        }

        // Hapus pengguna
        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200); // Ganti 201 dengan 200 (OK)
    } catch (\Exception $e) {
        // Tangkap exception dan kirim respons error
        return response()->json(['message' => 'Failed to delete user', 'error' => $e->getMessage()], 500); // Tambahkan pesan error
    }
});
