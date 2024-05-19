<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SubCategory;
use App\Models\Category;

use App\Models\TempImage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class SubCategoryController extends Controller
{
    public function index(Request $request){
        $subCategories=SubCategory::select('sub_categories.*','categories.name as categoryName')
        ->latest('sub_categories.id')
        ->leftJoin('categories','categories.id','sub_categories.category_id');
        if(!empty($request->get('keyword'))){

            $subCategories= $subCategories->where('sub_categories.name','like','%' .$request->get('keyword').'%');
            // $subCategories= $subCategories->orWhere('categories .name','like','%' .$request->get('keyword').'%');

        }

        $subCategories=$subCategories->paginate(10);
        return view('admin.sub_category.list',compact('subCategories'));
    }
    public function create(){
        $categories=Category::orderBy('name','ASC')->get();
        $data['categories']=$categories;
        return view('admin.sub_category.create',$data);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:sub_categories',
            'category' => 'required',
            'status' => 'required',

        ]);
        if ($validator->passes()){
            $subCategory=new SubCategory();
                $subCategory->name=$request->name;
                $subCategory->slug=$request->slug;
                $subCategory->status=$request->status;
                $subCategory->showHome=$request->showHome;

                $subCategory->category_id=$request->category;

                $subCategory->save();
                // $oldImage=$subCategory->image;
                // if(!empty($request->image_id)){
                //     $tempImage= TempImage::find($request->image_id);
                //     $extArray=explode('.',$tempImage->name);
                //     $ext=last($extArray);
                //     $newImageName=$subCategory->id.'-'.time().'.'.$ext;
                //         $sPath=public_path().'/temp/'.$tempImage->name;
                //         $dPath=public_path().'/uploads/category/'.$newImageName;

                //     File::copy($sPath,$dPath);


                //     $dPath=public_path().'/uploads/category/thumb/'.$newImageName;

                //     $img=Image::make($sPath);
                //     $img->fit(450, 600, function ($constraint) {
                //         $constraint->upsize();
                //     });
                //     $img->save($dPath);


                //     $category->image=$newImageName;
                // $category->save();
                
                //         File::delete(public_path().'/uploads/category/thumb/'.$oldImage);
                //         File::delete(public_path().'/uploads/category/'.$oldImage);

                // }

                session()->flash('success','Sub Category added succcessfully');
                //flash diye session msg banaise
                return response()->json([
                    'status' => true,
                    'message' =>'SubCategory added succcessfully'
                ]);
        }
        else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    
  
    public function edit($id,Request $request){

        $subCategory=SubCategory::find($id);

        if(empty($subCategory)){
            session()->flash('error','Record not found');
            return redirect()->route('sub-categories.index');
        }

        $categories=Category::orderBy('name','ASC')->get();
        $data['categories']=$categories;
        $data['subCategory']=$subCategory;
        return view('admin.sub_category.edit',$data);
       

    }
    public function update($id,Request $request){
        $subCategory=SubCategory::find($id);

        if(empty($subCategory)){
            session()->flash('error','Record not found');
            return response([
                'status' => false,
                'notFound'=>true,
                'message' =>'Category not found'
            ]);
        }

        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required|unique:categories,slug,'.$subCategory->id.',id',

            'category' => 'required',
            'status' => 'required',

        ]);

        if ($validator->passes()){
            $subCategory->name=$request->name;
            $subCategory->slug=$request->slug;
            $subCategory->status=$request->status;
            $subCategory->showHome=$request->showHome;

            $subCategory->category_id=$request->category;


            $subCategory->save();

                


                session()->flash('success','SubCategory updated succcessfully');
                //flash diye session msg banaise
                return response([
                    'status' => true,
                    'message' =>'SubCategory updated succcessfully'
                ]);

        }
        else {
            return response([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }


    public function destroy($id, Request $request){
        $subCategory = SubCategory::find($id);
    
        if(empty($subCategory)){
            session()->flash('error','Record not found');
            return response([
                'status' => true,
                'notFound' => true
            ]);
        }
    
        $subCategory->delete();
    
        session()->flash('success','Sub Category deleted successfully');
        return response([
            'status' => true,
            'message' => 'Sub Category deleted successfully'
        ]);
    }
    
}
