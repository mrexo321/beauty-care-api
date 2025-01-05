<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

     public function index()
     {
        $review = Review::all();
        return response()->json([
            'message' => 'Review Berhasil',
            'reviews' => $review
        ] , 200);
     }

     public function store(Request $request)
    {
        $validatedData = Validator::make($request->all(),[
            'user_id' => 'required|integer',
            'service_id' => 'required|string',
            'rating' => 'required|integer',
            'comment' => 'required|string',
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

        $review = Review::create([
            'user_id' => $request->input('user_id'),
            'service_id' => $request->input('service_id'),
            'rating' => $request->input('rating'),
           'comment' => $request->input('comment'),
        ]);

        if($review){
            return response()->json([
                'success' => true,
                'message' => 'Berhasil Menambah Data Review',
                'data' => $review
            ],201);
        }

    }

    public function show($id)
    {
        $review = Review::find($id);

        if ($review) {
            return response()->json([
                'success'   => true,
                'message'   => 'Detail Review',
                'data'      => $review
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Review Tidak Ditemukan!',
            ], 404);
        }
    }

    public function update(Request $request , $id){
        $validator = Validator::make($request->all(), [
            'user_id'      => 'required',
            'service_id'       => 'integer',
            'rating'             => 'integer',
            'comment'          => 'string',
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Semua Kolom Wajib Diisi!',
                'data'   => $validator->errors()
            ],401);

        } else {
            $review = Review::whereId($id)->update([
                'user_id'     => $request->input('user_id'),
                'service_id'   => $request->input('service_id'),
                'rating'   => $request->input('rating'),
                'comment'   => $request->input('comment'),
            ]);

            if ($review) {
                return response()->json([
                    'success' => true,
                    'message' => 'Review Berhasil Diupdate!',
                    'data' => $review
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Review Gagal Diupdate!',
                ], 400);
            }

        }
    }

    public function destroy($id)
    {
        $review = Review::whereId($id)->delete();
        if($review){
            return response()->json([
                'success' => true,
                'message' => 'Review Berhasil Dihapus!',
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Review Gagal Dihapus!',
            ], 200);
        }


    }


}
