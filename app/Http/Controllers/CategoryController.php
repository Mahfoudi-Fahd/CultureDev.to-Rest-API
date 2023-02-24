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
/**
 * @OA\Post(
 *     path="/api/categories",
 *     summary="Create a new category",
 *     description="Create a new category",
 *     tags={"Categories"},
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(
 *         required=true,
 *         description="Category object that needs to be created",
 *         @OA\JsonContent(
 *             @OA\Property(property="name", type="string", example="Technology"),
 *             @OA\Property(property="description", type="string", example="Articles about technology"),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Category created successfully",
 *         @OA\JsonContent(
 *             @OA\Property(property="status", type="boolean", example=true),
 *             @OA\Property(property="message", type="string", example="Category created successfully!"),
 *             @OA\Property(
 *                 property="category",
 *                 type="object",
 *             ),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized action",
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden action",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="This action is not allowed !"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Validation error",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="The given data was invalid."
 *             ),
 *             @OA\Property(
 *                 property="errors",
 *                 type="object",
 *             ),
 *         ),
 *     ),
 * )
 */

    
 
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
   
     /**
 * @OA\Get(
 *     path="/api/categories/{category}",
 *     summary="Get a specific category",
 *     description="Retrieve a specific category by ID",
 *     tags={"Categories"},
 *     @OA\Parameter(
 *         name="category",
 *         in="path",
 *         description="ID of the category to retrieve",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful operation",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="id",
 *                 type="integer",
 *                 description="ID of the category",
 *             ),
 *             @OA\Property(
 *                 property="name",
 *                 type="string",
 *                 description="Name of the category",
 *             ),
 *             @OA\Property(
 *                 property="created_at",
 *                 type="string",
 *                 description="Date and time of category creation (YYYY-MM-DD HH:MM:SS)",
 *             ),
 *             @OA\Property(
 *                 property="updated_at",
 *                 type="string",
 *                 description="Date and time of category update (YYYY-MM-DD HH:MM:SS)",
 *             ),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Category not found",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Category not found",
 *             ),
 *         ),
 *     ),
 * )
 */

    public function show(Category $category)
    {
        $category->find($category->id);
        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }
        return response()->json($category, 200); 
    }

    /**
 * @OA\Put(
 *     path="/api/categories/{category}",
 *     summary="Update a category",
 *     description="Update a category",
 *     tags={"Categories"},
 *     security={{"bearerAuth":{}}},
 *     @OA\Parameter(
 *         name="category",
 *         in="path",
 *         description="ID of the category to update",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64"
 *         )
 *     ),
 *     @OA\RequestBody(
 *         description="Category object to be updated",
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="id",
 *                 type="integer",
 *                 description="ID of the category",
 *             ),
 *             @OA\Property(
 *                 property="name",
 *                 type="string",
 *                 description="Name of the category",
 *             ),
 *             @OA\Property(
 *                 property="created_at",
 *                 type="string",
 *                 description="Date and time of category creation (YYYY-MM-DD HH:MM:SS)",
 *             ),
 *             @OA\Property(
 *                 property="updated_at",
 *                 type="string",
 *                 description="Date and time of category update (YYYY-MM-DD HH:MM:SS)",
 *             ),
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="The category is successfully updated",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="status",
 *                 type="boolean",
 *                 example=true,
 *             ),
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="success",
 *             ),
 *             @OA\Property(
 *                 property="category",
 *                 type="string",
 *                 example="Category Updated successfully !"
 *             )
 *         ),
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized action",
 *     ),
 *     @OA\Response(
 *         response=403,
 *         description="Forbidden action",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="This action is not allowed !"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Category not found",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Category not found"
 *             )
 *         )
 *     )
 * )
 */



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
