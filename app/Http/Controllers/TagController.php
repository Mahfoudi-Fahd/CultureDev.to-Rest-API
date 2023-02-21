<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Http\Requests\StoreTagRequest;
use App\Http\Requests\UpdateTagRequest;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::orderBy('id')->get();

        return response()->json([
            'status' => 'success',
            'tags' => $tags
        ]);    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreTagRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreTagRequest $request)
    {
        $tag = Tag::create($request->all());

        return response()->json([
            'status' => true,
            'message' => "Tag Created successfully!",
            'tag' => $tag
        ], 201);    
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        $tag->find($tag->id);
        if (!$tag) {
            return response()->json(['message' => 'Tag not found'], 404);
        }
        return response()->json($tag, 200);    
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function edit(Tag $tag)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateTagRequest  $request
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(StoreTagRequest $request, Tag $tag)
    {
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
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
