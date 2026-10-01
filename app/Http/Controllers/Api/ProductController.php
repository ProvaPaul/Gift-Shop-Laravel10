<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // GET /api/products?search=&category_id=&sub_category_id=&brand_id=&price_min=&price_max=&sort=latest|price_asc|price_desc&per_page=
    public function index(Request $request)
    {
        $products = Product::with('product_images')->where('status', 1);

        if ($request->filled('search')) {
            $products->where('title', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category_id')) {
            $products->where('category_id', $request->category_id);
        }
        if ($request->filled('sub_category_id')) {
            $products->where('sub_category_id', $request->sub_category_id);
        }
        if ($request->filled('brand_id')) {
            $products->whereIn('brand_id', explode(',', $request->brand_id));
        }
        if ($request->filled('price_min')) {
            $products->where('price', '>=', (float) $request->price_min);
        }
        if ($request->filled('price_max')) {
            $products->where('price', '<=', (float) $request->price_max);
        }

        match ($request->get('sort')) {
            'price_asc' => $products->orderBy('price', 'ASC'),
            'price_desc' => $products->orderBy('price', 'DESC'),
            default => $products->orderBy('id', 'DESC'),
        };

        $perPage = min((int) $request->get('per_page', 12), 50);

        return ProductResource::collection($products->paginate($perPage));
    }

    // GET /api/products/{id}
    public function show($id)
    {
        $product = Product::with('product_images')->where('status', 1)->findOrFail($id);

        return new ProductResource($product);
    }
}
