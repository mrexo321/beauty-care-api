<?php

namespace App\Http\Controllers;

use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function index()
    {
        $categories = Category::all();
        return response()->json([
            'success' => true,
            'message' =>' List Semua Service',
            'data'    => $categories
        ], 200);
    }


    //
}
