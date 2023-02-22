<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests\ArticleRequest;
use App\Models\Article;
use App\Models\Category;

class ArticleController extends Controller
{

    public function index()
    {
        $articles=Article::with('category')->get();
        return $articles;
    }


    public function store(ArticleRequest $request)
    {
        $article=Article::create($request->all());
        return response()->json([
            'message' => 'The article is succefully added !',
            'Article' => $article
        ], 201);
    }


    public function show(Article $article)
    {
        $article->category;
        if(!$article) return response()->json(['message'=>'No articles found !'], 404);
        return response()->json([
            'Article' => $article
        ], 200);  
    }


    public function update(ArticleRequest $request, article $article)
    {

        $article->update($request->all());
        $article->category;
        return response()->json([
            'message' => 'Article updated successfully :>',
            'Article' => $article
        ], 201);
    }


    public function destroy(Article $article)
    {
        article::destroy($article->id);
        return response()->json([
            'message' => 'Article deleted successfully :3'
        ]);
    }


    public function search($searching, Article $article){
        $article->category;
        $article->tag;
        $article=Article:: join('categories', 'categories.id', '=', 'articles.category_id')
                        //   ->join('tags', 'tags.id', '=', 'articles.tag_id')
                          ->where("title", "LIKE", "$searching%")
                          ->orwhere("name", "LIKE", "$searching%")
                          ->get();
        if(count($article)==0) return response()->json(["message"=>"No articles found :0"],404);

        return response()->json([
            "message"=>"Here's what you're looking for:",
            "Article"=> $article
        ],200);
    }
}
