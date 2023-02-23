<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Http\Requests\StoreTagRequest;

class TagController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except'=>['index','show']]);
    }


    public function index()
    {
        $tags = Tag::orderBy('id')->get();

        return response()->json([
            'status' => 'success',
            'tags' => $tags
        ]);    }




    public function store(StoreTagRequest $request)
    {
        if(auth()->user()->role_id!=1){
            return response()->json(["message"=>"This method not allowed !"]); 
        }else{
        $tag = Tag::create($request->all());

        return response()->json([
            'status' => true,
            'message' => "Tag Created successfully!",
            'tag' => $tag
        ], 201);
        }    
    }



    public function show(Tag $tag)
    {
        $tag->find($tag->id);
        if (!$tag) {
            return response()->json(['message' => 'Tag not found'], 404);
        }
        return response()->json($tag, 200);    
    }


    public function update(StoreTagRequest $request, Tag $tag)
    {
        if(auth()->user()->role_id!=1){
            return response()->json(["message"=>"This method not allowed !"]); 
        }else{
            $tag->update($request->all());

            if (!$tag) {
                return response()->json(['message' => 'Tag not found'], 404);
            }

            return response()->json([
                'status' => true,
                'message' => "Tag Updated successfully!",
                'tag' => $tag
            ], 200);    
        }
    }


    public function destroy(Tag $tag)
    {
        if(auth()->user()->role_id!=1){
            return response()->json(["message"=>"This method not allowed !"]); 
        }else{
            $tag->delete();

            if (!$tag) {
                return response()->json([
                    'message' => 'Tag not found'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Tag deleted successfully'
            ], 200);    
        }
    }
}
