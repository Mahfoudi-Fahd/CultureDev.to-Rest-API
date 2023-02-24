<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use App\Models\Tag;
use App\Models\User;
use App\Policies\ArticlePolicy;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api',['except'=>['index', 'show','searchByTitle', 'searchByTag','searchByCategory']]);
        $this->authorizeResource(Article::class, 'article');
    }

    public function index()
    {
        $articles=Article::with('category')->with('tags')->with('user')->with('comments')->get();

        return response()->json([
            'message'=>'All articles :',
             'Articles'=>$articles
        ]);
    }


    public function store(ArticleRequest $request)
    {
            $article=Article::create($request->all());
            $article->tags()->attach($request->tag_id);
            $article->category;
            $article->tags;
            $article->user;
            $article->comments;
            return response()->json([
                'message' => 'The article is succefully added !',
                'Article' => $article
            ], 201);

    }


    public function show(Article $article)
    {
        $article->category;
        $article->tags;
        $article->user;
        $article->comments;
        if(!$article) return response()->json(['message'=>'No articles found !'], 404);
        return response()->json([
            'message' =>'The article you looked for :',
            'Article' => $article
        ], 200);  
    }


    public function update(ArticleRequest $request, article $article)
    {
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


    public function destroy(Article $article){
       
        article::destroy($article->id);
        return response()->json([
            'message' => 'Article deleted successfully !'
        ]);
    }


    public function searchByTitle($searching, Article $article){

        $article=Article::where("title", "LIKE", "$searching%")->get();
        if(count($article)==0) return response()->json(["message"=>"No articles found :0"],404);
       
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

        $article=Tag::with('articles')->where("name", "LIKE", "$searching%")->first();

        if(!$article) return response()->json(["message"=>"No articles found :0"],404);
        return response()->json([
            "message"=>"Here's what you're looking for:",
            "Articles"=> $article->articles
        ],200);
    }
    
}
