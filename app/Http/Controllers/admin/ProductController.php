<?php

namespace App\Http\Controllers\admin;
use App\Models\ProductImage;
use App\Models\TempImage;
use Illuminate\Support\Facades\Validator;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use Intervention\Image\Facades\Image; // Import the Image facade
use Illuminate\Http\Request;


use App\Models\SubCategory;
use Illuminate\Support\Facades\File;




class ProductController extends Controller
{
    public function index(Request $request){
        // $products =Product::latest('id')->with('product_images')->paginate();

        $products =Product::latest('id')->with('product_images');
        // dd($products);
        if($request->get('keyword')!=""){
            $products=$products->where('title','like','%' .$request->keyword.'%');
        }
        $products = $products->paginate();
        $data['products']= $products;

        return view('admin.products.list',$data);
        
    }
    public function create(){
        $data=[];
        $categories=Category::orderBy('name','ASC')->get();
        $brands=Brand::orderBy('name','ASC')->get();

        $data['categories']=$categories;
        $data['brands']= $brands;
        return view('admin.products.create',$data);
    }
    public function store(Request $request){

        
// dd($request->image_array);
        $rules = [
            'title' => 'required',
            'slug' => 'required|unique:products',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products',
            'track_qty' => 'required||in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required||in:Yes,No',
           ];


           if(!empty($request->track_qty)&& $request->track_qty=='Yes'){
                $rules['qty']='required|numeric';
           }
            $validator=Validator::make($request->all(),$rules);

            if ($validator->passes()){
                    $product=new Product();
                        $product->title=$request->title;
                        $product->slug=$request->slug;
                        $product->description=$request->description;
                        $product->price=$request->price;
                        $product->compare_price=$request->compare_price;
                        $product->sku=$request->sku;
                        $product->barcode=$request->barcode;
                        $product->track_qty=$request->track_qty;
                        $product->qty=$request->qty;
                        $product->status=$request->status;

                         $product->category_id=$request->category;
                        $product->sub_category_id=$request->sub_category;
                        $product->brand_id=$request->brand;
                        $product->is_featured=$request->is_featured;

                        $product->shipping_returns=$request->shipping_returns;
                        $product->short_description=$request->short_description;
                        $product->related_products=(!empty($request->related_products)) ? implode(',',$request->related_products) : '';

                        


        
                        $product->save();



                        if(!empty($request->image_array)){
                            foreach($request->image_array as $temp_image_id){
                                $tempImageInfo= TempImage::find($temp_image_id);
                                $extArray=explode('.',$tempImageInfo->name);
                                $ext=last($extArray);



                                $productImage=new ProductImage();
                                $productImage->product_id=$product->id;
                                $productImage->image='NULL';
                                $productImage->save();

                                $imageName=$product->id.'-'.$productImage->id.'-'.time().'.'.$ext;
                                $productImage->image= $imageName;
                                $productImage->save();


                                $sPath=public_path().'/temp/'.$tempImageInfo->name;
                                $dPath=public_path().'/uploads/product/large/'.$imageName;
        
                            $image=Image::make($sPath);
        
        
                                    // $img->resize(450, 600);
                            $image->resize(1400,null, function ($constraint) {
                                $constraint->aspectRatio();
                            });
                            $image->save($dPath);

                            //small
                            $dPath=public_path().'/uploads/product/small/'.$imageName;
        
                            $image=Image::make($sPath);
        
        
                                    // $img->resize(450, 600);
                            $image->fit(300,300) ;
                            $image->save($dPath);





                            }
                        }
        
                      session()->flash('success','Product added succcessfully');

                        return response()->json([
                            'status' => true,
                            'errors' => $validator->errors()
                        ]);
        
                //         $request->session()->flash('success','Sub Category added succcessfully');
                //         return response()->json([
                //             'status' => true,
                //             'message' =>'product added succcessfully'
                //         ]);
                }
                else {
                    return response()->json([
                        'status' => false,
                        'errors' => $validator->errors()
                    ]);
                }
    
        }


        public function edit($id, Request $request)
        {
            $product = Product::find($id);

            if (empty($product)){
                // $request->session()->flash('error','Record not found');
                return redirect()->route('products.index')->with('error','Record not found');
            }

            $productImages = ProductImage::where('product_id', $product->id)->get();
            $subCategories = SubCategory::where('category_id', $product->category_id)->get();

            //fetch related product
            $relatedProducts =[];
            if($product->related_products !=''){
                $productArray= explode(',',$product->related_products);
                $relatedProducts =Product::whereIn('id',$productArray)->with('product_images')->get();
            }
        
            // Initialize $data as an empty array
            $data = [];
        
            // Assign values to $data using array key-value syntax
           
        
            $categories = Category::orderBy('name','ASC')->get();
            $brands = Brand::orderBy('name','ASC')->get();
        
            $data['categories'] = $categories;
            $data['brands'] = $brands;
            $data['product'] = $product;
            $data['subCategories'] = $subCategories;
            $data['productImages'] = $productImages;    
            $data['relatedProducts'] = $relatedProducts;

        
            // Return view with data
            return view('admin.products.edit', $data);
        }


        public function update($id,Request $request){
            $product=Product::find($id);
            
        

            $rules = [
                'title' => 'required',
                'slug' => 'required|unique:products,slug,'.$product->id.',id',
                'price' => 'required|numeric',
                'sku' => 'required|unique:products,sku,'.$product->id.',id',
                'track_qty' => 'required||in:Yes,No',
                'category' => 'required|numeric',
                'is_featured' => 'required||in:Yes,No',
               ];
    
    
               if(!empty($request->track_qty)&& $request->track_qty=='Yes'){
                    $rules['qty']='required|numeric';
               }
                $validator=Validator::make($request->all(),$rules);
    
                if ($validator->passes()){
                            $product->title=$request->title;
                            $product->slug=$request->slug;
                            $product->description=$request->description;
                            $product->price=$request->price;
                            $product->compare_price=$request->compare_price;
                            $product->sku=$request->sku;
                            $product->barcode=$request->barcode;
                            $product->track_qty=$request->track_qty;
                            $product->qty=$request->qty;
                            $product->status=$request->status;
    
                             $product->category_id=$request->category;
                            $product->sub_category_id=$request->sub_category;
                            $product->brand_id=$request->brand;
                            $product->is_featured=$request->is_featured;
                            $product->shipping_returns=$request->shipping_returns;
                            $product->short_description=$request->short_description;
                            $product->related_products=(!empty($request->related_products)) ? implode(',',$request->related_products) : '';


    
    
            
                            $product->save();
    
            
                           session()->flash('success','Product updated succcessfully');
    
                            return response()->json([
                                'status' => true,
                                'errors' => $validator->errors()
                            ]);
    
                    }
                    else {
                        return response()->json([
                            'status' => false,
                            'errors' => $validator->errors()
                        ]);
                    }
    
            
        }


        public function destroy($id,Request $request){
            $product=Product::find($id);
    
            if(empty($product)){

                session()->flash('error','Product not found');
    
                return response()->json([
                    'status' => true,
                    'notFound' => true
                ]);
            }

            
        $productImages = ProductImage::where('product_id',$id)->get();
        if(!empty($productImages)){
            foreach ($productImages as $productImage){
                File::delete(public_path().'uploads/product/large/'.$productImage->image);
                File::delete(public_path().'uploads/product/small/'.$productImage->image);

            }
            
            ProductImage::where('product_id',$id)->delete();

        }

        $product->delete();


    
    
            session()->flash('success','Product deleted succcessfully');
            return response()->json([
                'status' => true,
                'message' =>'Product deleted succcessfully'
            ]);
    
        }


        public function getProducts(Request $request){
            $tempProduct=[];
            if($request->term != ""){
                $products= Product::where('title','like','%'.$request->term.'%')->get();
            


            if($products!=null){
                foreach($products as $product){
                        $tempProduct[] =array('id' =>$product->id, 'text'=>$product->title);
                }
            }
        }
        return response()->json([
            'tags' => $tempProduct,
            'status' =>true
        ]);

        }
    
    
        

}
