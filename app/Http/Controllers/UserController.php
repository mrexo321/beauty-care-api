<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Portofolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

     public function store(Request $request)
     {
         $validatedData = Validator::make($request->all(),[
             'username' => 'required|string',
             'email' => 'required|email|unique:users,email',
             'password' => 'required|string',
             'role' => 'nullable'
         ]);

         // Jika validasi gagal
         if ($validatedData->fails()) {
             // Mengambil semua kesalahan validasi
             $errors = $validatedData->errors();
             // Menyusun pesan kesalahan per field
             $errorMessages = [];
             foreach ($errors->all() as $message) {
                 $errorMessages[] = $message;
             }
             return response()->json([
                 'success' => false,
                 'message' => 'Validation failed',
                 'errors' => $errorMessages,
             ], 422);
         }

         $user = User::create([
             'username' => $request->input('username'),
             'email' => $request->input('email'),
             'password' => Hash::make($request->input('password')),
             'role' => $request->input('role'),
         ]);

         if($user){
             return response()->json([
                 'success' => true,
                 'message' => 'Berhasil Menambah Data User',
                 'data' => $user
             ],201);
         }

     }

     public function register(Request $request)
     {
        $validatedData = Validator::make($request->all() , [
        'username' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6',
        'description' => 'nullable|string|max:500',
        ]);
        $user = User::create([
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'description' => $request->input('description') ?? null,
            'role' => 'user', // role selalu 'user'
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Registration successful',
            'user' => $user,
        ], 201);
     }

     public function authenticate(Request $request)
     {
        $validatedData = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        // Check kredensial user
        if ($authorized = Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            return response()->json([
                'success' => true,
                'message' => 'Login Berhasil',
                'user' => $user,
                'token' => $this->respondWithToken($authorized)
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Email/Password salah'
        ] , 401);
     }


     public function staff()
     {
        $staffs = User::where('role' , 'staff')->get();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil Mendapatkan Staff',
            'staffs' => $staffs
        ],200);
     }

     public function getPortofolio($id)
    {
     $portofolio =  User::with('portofolios')
                    ->where('id', $id)
                    ->where('role' , 'staff')
                    ->first();

        if (!$portofolio) {
            return response()->json([
                'message' => 'Portofolio not found'
            ], 404);
        }

        return response()->json([
            'message' => 'Sukses Fetch Portofolio',
            'data' => $portofolio,
            'success' => true
        ], 200);
    }

    public function user_detail($id)
    {
        $user = User::find($id);

        if ($user) {
            return response()->json([
                'success'   => true,
                'message'   => 'Detail User',
                'data'      => $user
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User Tidak Ditemukan!',
            ], 404);
        }
    }

    public function addNewStaff(Request $request)
    {
        $validatedData = Validator::make($request->all(),[
            'username' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string',
            'description' => 'nullable',
            'role' => 'string|nullable'
        ]);

        // Jika validasi gagal
        if ($validatedData->fails()) {
            // Mengambil semua kesalahan validasi
            $errors = $validatedData->errors();
            // Menyusun pesan kesalahan per field
            $errorMessages = [];
            foreach ($errors->all() as $message) {
                $errorMessages[] = $message;
            }
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $errorMessages,
            ], 422);
        }

        // Tentukan role sebagai 'staff' sebelum membuat user
        $role = 'staff';  // Nilai role ditetapkan langsung sebagai 'staff'

        // Membuat user baru dengan role 'staff'
        $user = User::create([
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'description' => $request->input('description'),
            'password' => Hash::make($request->input('password')),
            'role' => $role,  // Pastikan role selalu 'staff'
        ]);

        if ($user) {
            return response()->json([
                'success' => true,
                'message' => 'Berhasil Menambah Data Staff',
                'data' => $user
            ], 201);
        }
    }

    public function updateStaff($id, Request $request)
    {
    $validatedData = Validator::make($request->all() , [
        'username' => 'required|string',
        'email' => 'required|email',
        'description' => 'nullable',
        'password' => 'nullable|string',
    ]);

        if ($validatedData->fails()) {
            // Mengambil semua kesalahan validasi
            $errors = $validatedData->errors();
            // Menyusun pesan kesalahan per field
            $errorMessages = [];
            foreach ($errors->all() as $message) {
                $errorMessages[] = $message;
            }
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $errorMessages,
            ], 422);
        }

    $staff = User::findOrFail($id);
    $staff->username = $request->input('username');
    $staff->email = $request->input('email');
    $staff->description = $request->input('description');
    $staff->role = "staff";

    if ($request->has('password') && $request->input('password') !== '') {
        $staff->password = Hash::make($request->input('password'));
    }

    $staff->save();

    return response()->json([
        'success' => true,
        'message' => 'Staff updated successfully',
        'data' => $staff,
    ]);
}

    public function staffDetail($id)
    {
        $staff = User::where('id' , $id)->where('role' , 'staff')->first();
        return response()->json([
            'message' => "fetch data staff berhasil",
            "data" => $staff
        ],200);
    }



}
