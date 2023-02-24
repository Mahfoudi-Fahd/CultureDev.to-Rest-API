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



    /**
 * @OA\Post(
 *     path="/api/tags",
 *     summary="Create a tag",
 *     description="Create a new tag",
 *     operationId="createTag",
 *     tags={"Tags"},
 *     security={
 *         {"Bearer": {}}
 *     },
 *     @OA\RequestBody(
 *         description="Tag object that needs to be added",
 *         required=true,
 *         @OA\JsonContent(
 *              type="object",
 *                  @OA\Property(
 *                     property="id",
 *                     type="integer",
 *                     description="ID of the tag."
 *                     ),
 *                     @OA\Property(
 *                     property="name",
 *                     type="string",
 *                     description="Name of the tag."
 *                     ),
 *          ),
 *     ),
 *     @OA\Response(
 *         response=201,
 *         description="Tag created successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="status",
 *                 type="boolean",
 *                 example=true
 *             ),
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Tag created successfully"
 *             ),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Invalid input"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Unauthorized"
 *             )
 *         )
 *     )
 * )
 */

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


   /**
 * @OA\Get(
 *     path="/api/tags/{tag}",
 *     summary="Get a tag by ID",
 *     description="Returns a single tag",
 *     operationId="getTagById",
 *     tags={"Tags"},
 *     security={
 *         {"Bearer": {}}
 *     },
 *     @OA\Parameter(
 *         name="tag",
 *         in="path",
 *         description="ID of the tag to return",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64",
 *         ),
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Tag found",
 *         @OA\JsonContent(
 *             @OA\Property(
 *               property="id",
 *               type="integer",
 *               description="ID of the tag."
 *               ),
 *               @OA\Property(
 *               property="name",
 *               type="string",
 *               description="Name of the tag."
 *              )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Tag not found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Tag not found"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Unauthorized"
 *             )
 *         )
 *     )
 * )
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
 * @OA\Put(
 *     path="/api/tags/{tag}",
 *     summary="Update a tag",
 *     description="Updates an existing tag by ID",
 *     operationId="updateTagById",
 *     tags={"Tags"},
 *     security={
 *         {"Bearer": {}}
 *     },
 *     @OA\Parameter(
 *         name="tag",
 *         in="path",
 *         description="ID of the tag to update",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64"
 *         )
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         description="Tag object that needs to be updated",
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Tag updated successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="status",
 *                 type="boolean",
 *                 example=true
 *             ),
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Tag updated successfully!"
 *             ),
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Tag not found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Tag not found"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Unauthorized"
 *             )
 *         )
 *     )
 * )
 */


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
/**
 * @OA\Delete(
 *     path="/api/tags/{tag}",
 *     summary="Delete a tag by ID",
 *     description="Delete a tag by its ID.",
 *     operationId="deleteTag",
 *     tags={"Tags"},
 *     security={{"bearerAuth": {}}},
 *     @OA\Parameter(
 *         name="tag",
 *         in="path",
 *         description="ID of the tag to delete",
 *         required=true,
 *         @OA\Schema(
 *             type="integer"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Tag deleted successfully",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="status",
 *                 type="boolean",
 *                 example=true,
 *                 description="Whether the operation was successful"
 *             ),
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Tag deleted successfully",
 *                 description="A message indicating the result of the operation"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Tag not found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Tag not found",
 *                 description="A message indicating that the tag was not found"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=401,
 *         description="Unauthorized",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="This method not allowed !",
 *                 description="A message indicating that the user is not authorized to perform this action"
 *             )
 *         )
 *     )
 * )
 */


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
