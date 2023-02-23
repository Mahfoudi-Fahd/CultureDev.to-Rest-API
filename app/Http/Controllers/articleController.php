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
