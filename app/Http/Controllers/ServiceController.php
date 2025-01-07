<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::all();
        return response()->json([
            'success' => true,
            'message' =>' List Semua Service',
            'data'    => $services
        ], 200);
    }

    public function store(Request $request)
    {
        // Validasi data input
        $validatedData = Validator::make($request->all(), [
            'service_name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|integer',
            'duration' => 'required|integer',
            'image' => 'nullable|image',
        ]);

        // Jika validasi gagal
        if ($validatedData->fails()) {
            $errors = $validatedData->errors();
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

        // Proses upload gambar jika ada
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            // Menyimpan gambar ke folder storage/app/public/images
            $imagePath = $image->store('images', 'public');
        }

        // Menyimpan data service
        $service = Service::create([
            'service_name' => $request->input('service_name'),
            'description' => $request->input('description'),
            'price' => $request->input('price'),
            'duration' => $request->input('duration'),
            'category_id' => $request->input('category_id'),
            'image' => $imagePath, // Menyimpan path gambar
        ]);

        if ($service) {
            return response()->json([
                'success' => true,
                'message' => 'Service added successfully',
                'data' => $service
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to add service'
        ], 500);
    }

    public function show($id)
    {
        $service = Service::find($id);

        if ($service) {
            return response()->json([
                'success'   => true,
                'message'   => 'Detail Service',
                'data'      => $service
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Service Tidak Ditemukan!',
            ], 404);
        }
    }

    public function update(Request $request , $id){
        $validator = Validator::make($request->all(), [
            'service_name'      => 'required',
            'description'       => 'string',
            'price'             => 'integer',
            'duration'          => 'integer',
            'category_id'       => 'integer'
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Semua Kolom Wajib Diisi!',
                'data'   => $validator->errors()
            ],401);

        } else {
            $service = Service::whereId($id)->update([
                'service_name'     => $request->input('service_name'),
                'description'   => $request->input('description'),
                'price'   => $request->input('price'),
                'duration'   => $request->input('duration'),
                'category_id'   => $request->input('category_id'),
            ]);

            if ($service) {
                return response()->json([
                    'success' => true,
                    'message' => 'Service Berhasil Diupdate!',
                    'data' => $service
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Service Gagal Diupdate!',
                ], 400);
            }

        }
    }

    public function destroy($id)
    {
        $service = Service::whereId($id)->delete();
        if($service){
            return response()->json([
                'success' => true,
                'message' => 'Service Berhasil Dihapus!',
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Service Gagal Dihapus!',
            ], 200);
        }


    }

    public function review($id)
    {
        $reviews = Review::where('service_id' , $id)->get();

        return response()->json([
            'message' => 'berhasil mendapatkan review',
            'data' => $reviews
        ],200);
    }
}
