<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;


class CategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api', ['except'=>['index','show']]);
    }


    public function index()
    {
        
        $categories = Category::orderBy('id')->get();

        return response()->json([
            'status' => 'success',
            'categories' => $categories
        ]); 
    }


 
    public function store(StoreCategoryRequest $request)
    {
        if(auth()->user()->role_id!=1){
            return response()->json(["message"=>"This method not allowed !"]); 
        }else{
            $category = Category::create($request->all());

            return response()->json([
                'status' => true,
                'message' => "Category Created successfully!",
                'category' => $category
            ], 201);   
        } 
    }


    public function show(Category $category)
    {
        $category->find($category->id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        return response()->json($category, 200); 
    }



    public function update(StoreCategoryRequest $request, Category $category)
    {
        if(auth()->user()->role_id!=1){
              return response()->json(["message"=>"This method not allowed !"]); 
        }else{
            $category->update($request->all());

            if (!$category) {
                return response()->json(['message' => 'Category not found'], 404);
            }

            return response()->json([
                'status' => true,
                'message' => "Category Updated successfully!",
                'category' => $category,
            ], 200);  
        }
     }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        if(auth()->user()->role_id!=1){
            return response()->json(["message"=>"This method not allowed !"]); 
        }else{
            $category->delete();

            if (!$category) {
                return response()->json([
                    'message' => 'Category not found'
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'Category deleted successfully'
            ], 200);  
        }  
    }
}
