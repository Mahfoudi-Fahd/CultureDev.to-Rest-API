<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use App\Models\Tag;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api',['except'=>['index', 'show','searchByTitle', 'searchByTag','searchByCategory']]);
    }

    public function index()
    {
        $articles=Article::with('category')->with('tags')->with('user')->with('comments')->get();
        return response()->json([
            'message'=>'All articles :',
             'Articles'=>$articles
        ]);
        // $a=Tag::find(1);
        // foreach($a->articles as $t){
        //     echo $t->pivot->get();
        // }
    }
    
    /**
     * @OA\Post(
     *     path="/api/articles",
     *     summary="Create a new article",
     *     description="Create a new article",
     *     tags={"Articles"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         description="create a new article",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="The article is successfully added",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="status",
     *                 type="string",
     *                 description="the response status",
     *                 example="success",
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 description="message describe the status of response",
     *                 example="The article is successfully added !",
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
     *     )
     * )
 */

    public function store(ArticleRequest $request)
    {
        if(auth()->user()->role_id==3){
            return response()->json(['message'=>'This action is not allowed !']);
        }else{
            $article=Article::create($request->all());
            $article->category;
            $article->tags;
            $article->user;
            $article->comments;
            return response()->json([
                'message' => 'The article is succefully added !',
                'Article' => $article
            ], 201);
        }
 
    }
    
     /**
 * @OA\Get(
 *     path="/api/articles/{article}",
 *     summary="Show a specific article",
 *     description="Returns the details of a specific article.",
 *     tags={"Articles"},
 *     @OA\Parameter(
 *         name="article",
 *         in="path",
 *         description="ID of article to return",
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
 *                 property="Article",
 *                 type="object",
 *                 description="Details of the article.",
 *                 @OA\Property(
 *                     property="id",
 *                     type="integer",
 *                     description="ID of the article."
 *                 ),
 *                 @OA\Property(
 *                     property="title",
 *                     type="string",
 *                     description="Title of the article."
 *                 ),
 *                 @OA\Property(
 *                     property="body",
 *                     type="string",
 *                     description="Body of the article."
 *                 ),
 *                 @OA\Property(
 *                     property="category",
 *                     type="object",
 *                     description="Details of the article's category.",
 *                     @OA\Property(
 *                         property="id",
 *                         type="integer",
 *                         description="ID of the category."
 *                     ),
 *                     @OA\Property(
 *                         property="name",
 *                         type="string",
 *                         description="Name of the category."
 *                     )
 *                 ),
 *                 @OA\Property(
 *                     property="tags",
 *                     type="array",
 *                     description="Array of tags associated with the article.",
 *                     @OA\Items(
 *                         type="object",
 *                         @OA\Property(
 *                             property="id",
 *                             type="integer",
 *                             description="ID of the tag."
 *                         ),
 *                         @OA\Property(
 *                             property="name",
 *                             type="string",
 *                             description="Name of the tag."
 *                         )
 *                     )
 *                 ),
 *                 @OA\Property(
 *                     property="user",
 *                     type="object",
 *                     description="Details of the user who created the article.",
 *                     @OA\Property(
 *                         property="id",
 *                         type="integer",
 *                         description="ID of the user."
 *                     ),
 *                     @OA\Property(
 *                         property="name",
 *                         type="string",
 *                         description="Name of the user."
 *                     )
 *                 ),
 *                 @OA\Property(
 *                     property="comments",
 *                     type="array",
 *                     description="Array of comments associated with the article.",
 *                     @OA\Items(
 *                         type="object",
 *                         @OA\Property(
 *                             property="id",
 *                             type="integer",
 *                             description="ID of the comment."
 *                         ),
 *                         @OA\Property(
 *                             property="body",
 *                             type="string",
 *                             description="Body of the comment."
 *                         ),
 *                         @OA\Property(
 *                             property="user",
 *                             type="object",
 *                             description="Details of the user who created the comment.",
 *                             @OA\Property(
 *                                 property="id",
 *                                 type="integer",
 *                                 description="ID of the user."
 *                             ),
 *                             @OA\Property(
 *                                 property="name",
 *                                 type="string",
 *                                 description="Name of the user."
 *                             )
 *                        ),
 *                      ),
 *                     ),
 *                   ),
 *                 ),
 *        ),
 *        @OA\Response(
 *        response=404,
 *        description="user not found",
 *            @OA\Property(
 *                property="status",
 *                type="string",
 *                description="status of response",
 *                example="not found",
 *            ),
 *            @OA\Property(
 *                property="message",
 *                type="string",
 *                description="a message describe the status of the response",
 *                example="article not found",
 *            ),
 *        ),
 *       @OA\Response(
 *           response=401,
 *           description="Anuthorize action",
 *       )
 * 
 * ),
 * 
*/

    public function show(Article $article)
    {
        $article->category;
        $article->tags;
        $article->user;
        $article->comments;
        if(!$article) return response()->json(['message'=>'No articles found !'], 404);
        return response()->json([
            'Article' => $article
        ], 200);  
    }


    public function update(ArticleRequest $request, article $article)
    {
        if(auth()->user()->role_id==3){
            return response()->json(['message'=>'This action is Not allowed']);
        }else if(auth()->user()->role_id==2){
            if(auth()->user()->id == $article->user_id){
                $article->update($request->all());
                $article->category;
                $article->tags;
                $article->user;
                $article->comments;
                return response()->json([
                    'message' => 'Article updated successfully !',
                    'Article' => $article
                ], 201);
            }else return response()->json(['message'=>'This action is not allowed !']);
        }else if(auth()->user()->role_id==1){
            $article->update($request->all());
            $article->category;
            $article->tags;
            $article->user;
            $article->comments;
            return response()->json([
                'message' => 'Article updated successfully !',
                'Article' => $article
            ], 201);
        }
    }


    public function destroy(Article $article)
    {
       
        if(auth()->user()->role_id==3){
           return response()->json(['message'=>'This action is Not allowed']);
        }else if(auth()->user()->role_id==2){
            if(auth()->user()->id == $article->user_id){
                article::destroy($article->id);
                return response()->json([
                    'message' => 'Article deleted successfully !'
                ]);
                }else return response()->json(['message'=>'This action is Not allowed']);
        }else if(auth()->user()->role_id==1){
            article::destroy($article->id);
            return response()->json([
                'message' => 'Article deleted successfully !'
            ]);
        }

    }


    public function searchByTitle($searching, Article $article){

        $article=Article::where("title", "LIKE", "$searching%")->get();
        if(count($article)==0) return response()->json(["message"=>"No articles found :0"],404);
        // $article->category;
        // $article->tags;
        return response()->json([
            "message"=>"Here's what you're looking for:",
            "Article"=> $article
        ],200);
    }

    public function searchByCategory($searching, Article $article){

        $article=Article::join('categories', 'categories.id', '=', 'articles.category_id')
                          ->where("name", "LIKE", "$searching%")
                          ->get();

        if(count($article)==0) return response()->json(["message"=>"No articles found :0"],404);
        return response()->json([
            "message"=>"Here's what you're looking for:",
            "Article"=> $article
        ],200);
    }

    public function searchByTag($searching, Article $article){

        $article=Article::join('tags', 'tags.id', '=', 'articles.tag_id')
                          ->where("name", "LIKE", "$searching%")
                          ->get();
        
        if(count($article)==0) return response()->json(["message"=>"No articles found :0"],404);
        return response()->json([
            "message"=>"Here's what you're looking for:",
            "Article"=> $article
        ],200);
    }
    
}
