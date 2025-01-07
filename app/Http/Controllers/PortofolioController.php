<?php

namespace App\Http\Controllers;

use App\Models\Portofolio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PortofolioController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

     public function index()
    {
        $portofolio = Portofolio::all();
        return response()->json([
            'success' => true,
            'message' =>' List Semua Portofolio',
            'data'    => $portofolio
        ], 200);
    }

    public function store(Request $request)
    {
        $validatedData = Validator::make($request->all(),[
            'user_id' => 'required|string',
            'title' => 'required|string',
            'description' => 'required|string',
            'image' => 'string|nullable',
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

        $portofolio = Portofolio::create([
            'user_id' => $request->input('user_id'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
           'image' => $request->input('image'),
        ]);

        if($portofolio){
            return response()->json([
                'success' => true,
                'message' => 'Berhasil Menambah Data portofolio',
                'data' => $portofolio
            ],201);
        }

    }

    public function show($id)
    {
        $portofolio = Portofolio::find($id);

        if ($portofolio) {
            return response()->json([
                'success'   => true,
                'message'   => 'Detail portofl$portofolio',
                'data'      => $portofolio
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'portofolio Tidak Ditemukan!',
            ], 404);
        }
    }

    public function update(Request $request , $id){
        $validator = Validator::make($request->all(), [
            'user_id'           => 'required',
            'title'             => 'string',
            'description'       => 'string',
            'image'             => 'string',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Semua Kolom Wajib Diisi!',
                'data'   => $validator->errors()
            ],401);

        } else {
            $service = Service::whereId($id)->update([
                'user_id'     => $request->input('user_id'),
                'title'   => $request->input('title'),
                'description'   => $request->input('description'),
                'image'   => $request->input('image'),
            ]);

            if ($portofolio) {
                return response()->json([
                    'success' => true,
                    'message' => 'portofolio Berhasil Diupdate!',
                    'data' => $portofolio
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Portofolio Gagal Diupdate!',
                ], 400);
            }

        }
    }

    public function destroy($id)
    {
        $portofolio = Portofolio::whereId($id)->delete();
        if($portofolio){
            return response()->json([
                'success' => true,
                'message' => 'Portofolio Berhasil Dihapus!',
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Portofolio Gagal Dihapus!',
            ], 200);
        }
    }
}
