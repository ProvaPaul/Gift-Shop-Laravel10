<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        return CategoryResource::collection(
            Category::with('sub_category')->where('status', 1)->orderBy('name')->get()
        );
    }

    public function show($id)
    {
        return new CategoryResource(
            Category::with('sub_category')->where('status', 1)->findOrFail($id)
        );
    }
}
