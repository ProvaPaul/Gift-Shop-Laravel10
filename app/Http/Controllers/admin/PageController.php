<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
class PageController extends Controller
{
    public function index(Request $request){
        $pages=Page::latest();
        if(!empty($request->get('keyword'))){
            $pages=$pages->where('name','like','%' .$request->get('keyword').'%');
        }
        $pages=$pages->paginate(10);
        return view('admin.pages.list',[
            'pages' => $pages
        ]);
    }

    public function create(){
        return view('admin.pages.create');
    }
    
    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required',
        ]);
        if ($validator->passes()){
                $page=new Page;
                $page->name=$request->name;
                $page->slug=$request->slug;
                $page->content=$request->content;
                $page->save();



                session()->flash('success','page added succcessfully');
                //flash diye session msg banaise
                return response()->json([
                    'status' => true,
                    'message' =>'page added succcessfully'
                ]);

        }
        else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function edit($pageId,Request $request){
        $page=Page::find($pageId);

        if(empty($page)){
           session()->flash('error','page not found');

            return redirect()->route('pages.index');
        }
        return view('admin.pages.edit',[
            'page' => $page

        ]);

    }
    public function update(Request $request,$pageId){
        $page=Page::find($pageId);

        if(empty($page)){
            session()->flash('error','page not found');

            return response()->json([
                'status' => true,
                
            ]);

        }
        $validator = Validator::make($request->all(),[
            'name' => 'required',
            'slug' => 'required',
        ]);

        if ($validator->passes()){
            $page->name=$request->name;
            $page->slug=$request->slug;
            $page->content=$request->content;
            $page->save();



           session()->flash('success','page updated succcessfully');
            //flash diye session msg banaise
            return response()->json([
                'status' => true,
                'message' =>'page updated succcessfully'
            ]);

    }
    else {
        return response()->json([
            'status' => false,
            'errors' => $validator->errors()
        ]);
    }

    }


    public function destroy($pageId,Request $request){
        $page=Page::find($pageId);

        if(empty($page)){
            session()->flash('error','page not found');

            return response()->json([
                'status' => true,
                'message' =>'page not found'
            ]);
        }
        
        $page->delete();
        
        session()->flash('success','page deleted succcessfully');
        return response()->json([
            'status' => true,
            'message' =>'page deleted succcessfully'
        ]);

    }
    // In one of your controllers



}
