<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $comments = Comment::all();

        return response()->json([
            'status' => 'success',
            'comments' => $comments,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    /**
 * @OA\Post(
 *     path="/api/comments",
 *     summary="Create a comment",
 *     description="Create a new comment for an article",
 *     operationId="createComment",
 *     tags={"Comments"},
 *     security={
 *         {"Bearer": {}}
 *     },
 *     @OA\RequestBody(
 *         description="Comment object",
 *         required=true,
 *         @OA\JsonContent(
 *             required={"article_id", "content"},
 *             @OA\Property(property="article_id", type="integer", example=1),
 *             @OA\Property(property="content", type="string", example="This is a comment")
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Comment created successfully",
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
 *                 example="success"
 *             ),
 *             @OA\Property(
 *                 property="comments",
 *                 ref="Commment Created successfully!"
 *             )
 *         )
 *     )
 * )
 */

    public function store(Request $request)
    {
        $comments = Comment::create([
            'user_id' => auth()->user()->id,
            'article_id' => $request->article_id,
            'content' => $request->content,
        ]);

        return response()->json([
            'status' => true,
            'message' => "Commment Created successfully!",
            'comments' => $comments,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    /**
 * @OA\Get(
 *     path="/api/comments/{id}",
 *     summary="Get a comment",
 *     description="Get a comment by ID",
 *     operationId="getCommentById",
 *     tags={"Comments"},
 *     security={
 *         {"Bearer": {}}
 *     },
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the comment to get",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Comment found",
 *         @OA\JsonContent(
 *               type="object",
 *               @OA\Property(
 *               property="id",
 *               type="integer",
 *               description="ID of the comment."
 *               ),
 *               @OA\Property(
 *               property="body",
 *               type="string",
 *               description="Body of the comment."
 *               ),
 *         ),
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Comment not found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Comment not found"
 *             )
 *         )
 *     )
 * )
 */

    public function show(Comment $comment)
    {
        $comment->find($comment->id);
        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }
        return response()->json(
            $comment,
            200
        );
    }
    // public function showComments(Comment $comment)
    // {
    //     $comment->find($comment->id);
    //     if (!$comment) {
    //         return response()->json(['message' => 'Comment not found'], 404);
    //     }
    //     return response()->json(
    //         $comment,
    //         200
    //     );
    // }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        // $comment->update($request->all());

        // if (!$comment) {
        //     return response()->json(['message' => 'Comment not found'], 404);
        // }

        // return response()->json([
        //     'status' => true,
        //     'message' => "Comment Updated successfully!",
        //     'comment' => $comment
        // ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    /**
 * @OA\Delete(
 *     path="/api/comments/{id}",
 *     summary="Delete a comment",
 *     description="Delete a comment by ID",
 *     operationId="deleteCommentById",
 *     tags={"Comments"},
 *     security={
 *         {"Bearer": {}}
 *     },
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the comment to delete",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Comment deleted successfully",
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
 *                 example="Comment deleted successfully"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Comment not found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Comment not found"
 *             )
 *         )
 *     )
 * )
 */

    public function destroy(Comment $comment)
    {
        $comment->delete();

        if (!$comment) {
            return response()->json([
                'message' => 'Comment not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Comment deleted successfully'
        ], 200);
    }
}
