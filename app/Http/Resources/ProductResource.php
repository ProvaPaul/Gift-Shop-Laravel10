<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'short_description' => $this->short_description,
            'price' => $this->price,
            'compare_price' => $this->compare_price,
            'category_id' => $this->category_id,
            'sub_category_id' => $this->sub_category_id,
            'brand_id' => $this->brand_id,
            'is_featured' => $this->is_featured,
            'sku' => $this->sku,
            'qty' => $this->qty,
            'images' => $this->product_images->map(fn ($img) => asset('uploads/product/small/' . $img->image))->values(),
        ];
    }
}
