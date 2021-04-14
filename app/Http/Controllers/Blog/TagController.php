<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index(){
        
        return Tag::orderBy('created_at', 'desc')->get();

    }

    public function create($request){

        return Tag::create([
            'name' => $request['name']
        ]);

    }

    public function store(Request $request){

        $tag = $this->create($request->all());

        return response()->json(['message' => 'Created Successfully', 'tag' => $tag]);

    }

    public function update(Request $request, $id){

        $tag = Tag::find($id);
        $tag->name = $request->name;
        $tag->save();

        return response()->json(['message' => 'Updated Successfully', 'tag' => $tag]);
        
    }

    public function destroy($id){

        $tag = Tag::find($id)->delete();

        return response()->json(['message' => 'Deleted Successfully', 'tag' => $tag]);

    }
}
