<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class CategoryController extends Controller
{
    public function index(){

        return Category::orderBy('created_at', 'desc')->get();

    }

    public function create($request){

        $img_path = Carbon::parse(Carbon::now())->format('Y-m-d').'_IMG_'.rand().'-'.$request['image']->getClientOriginalName();

        $request['image']->move(public_path('img'), $img_path);

        return Category::create([
            'name' => $request['name'],
            'image' => $img_path
        ]);

    }

    public function store(Request $request){

       $category = $this->create($request->all());

       return response()->json(['msg' => 'Created Successfully', 'category' => $category]);
       
    }


    public function update(Request $request, $id){

        $category = Category::find($id);

        if($request->hasFile('image')){
            $img_path = Carbon::parse(Carbon::now())->format('Y-m-d').'_IMG_'.rand().'-'.$request['image']->getClientOriginalName();
            $request['image']->move(public_path('img'), $img_path);

            // $img_remove = public_path().'/img/'.$category->image;
            // if(file_exists($img_remove)){
            //     @unlink($img_remove);
            // }

            $this->removeImgFromServer($category->image);

            $category->image = $img_path;
        }

        $category->name = $request->name;
        
        $category->save();

        return response()->json(['message' => 'Updated Successfully', 'category' =>  $category]);
    }

    public function removeImgFromServer($img){

        $img_path = public_path().'/img/'.$img;
        if(file_exists($img_path)){
            @unlink($img_path);
        }

        return ;
    } 

    public function destroy(Request $request, $id){

        $this->removeImgFromServer($request->image);

        $category = Category::find($id)->delete();

        return response()->json(['msg' => 'Deleted Successfully', 'category' => $category]);
        
    }
}
