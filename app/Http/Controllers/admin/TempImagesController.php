<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TempImage;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image; // Import the Image facade

class TempImagesController extends Controller
{
    public function create(Request $request)
    {
        $image = $request->file('image');

        if (!empty($image)) {
            $ext = $image->getClientOriginalExtension();
            $newName = time() . '.' . $ext;

            $tempImage = new TempImage();
            $tempImage->name = $newName;
            $tempImage->save();

            // Attempt to move the image to the desired directory
            $image->move(public_path().'/temp', $newName);

            $sourcePath=public_path().'/temp/'.$newName;
            $destPath=public_path().'/temp/thumb/'.$newName;

            // Use Image facade with correct namespace
            $image = Image::make($sourcePath);
            $image->fit(300,275);
            $image->save($destPath);

            return response()->json([
                'status' => true,
                'image_id' => $tempImage->id,
                'ImagePath' => asset('/temp/thumb/'.$newName), // Corrected path
                'message' => 'Image uploaded successfully'
            ]);
        } else {
            // No image found in the request
            return response()->json([
                'status' => false,
                'message' => 'No image found in the request'
            ], 400);
        }
    }
}
