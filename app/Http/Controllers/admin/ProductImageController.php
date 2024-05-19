<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductImage;
use App\Models\Product;
use Intervention\Image\Facades\Image; // Import the Image facade

use Illuminate\Support\Facades\File;


class ProductImageController extends Controller
{
    public function update(Request $request){
        $image = $request->image;

        $ext = $image->getClientOriginalExtension();
        $sPath= $image->getPathName();


    $productImage=new ProductImage();
    $productImage->product_id=$request->product_id;
    $productImage->image='NULL';
    $productImage->save();

    $imageName=$request->product_id.'-'.$productImage->id.'-'.time().'.'.$ext;
    $productImage->image=$imageName;
    $productImage->save();

    $dPath=public_path().'/uploads/product/large/'.$imageName;

$image=Image::make($sPath);


        // $img->resize(450, 600);
$image->resize(1400,null, function ($constraint) {
    $constraint->aspectRatio();
});
$image->save($dPath);
$dPath=public_path().'/uploads/product/small/'.$imageName;
        
                            $image=Image::make($sPath);
        
        
                                    // $img->resize(450, 600);
                            $image->fit(300,300) ;
                            $image->save($dPath);
                            return response()->json([
                                'status' => true,
                                'image_id'=> $productImage->id,
                                'ImagePath' => asset('uploads/product/small/'.$productImage->image), // Corrected path

                                'message' =>'succesful'
                            ]);



    }

    public function destroy(Request $request){
        $productImage= ProductImage::find($request->id);

        if(empty($productImage)){
            // $request->session()->flash('error','Category not found');

            return response()->json([
                'status' => false ,
                'message' =>'Image not found'
            ]);
            // return redirect()->route('categories.index');
        }
        File::delete(public_path().'uploads/product/large/'.$productImage->image);
        File::delete(public_path().'uploads/product/small/'.$productImage->image);
        $productImage->delete();


        // $request->session()->flash('success','Category deleted succcessfully');
        return response()->json([
            'status' => true,
            'message' =>'image deleted succcessfully'
        ]);

    }
}
