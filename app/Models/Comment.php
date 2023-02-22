<?php

namespace App\Models;

use App\Http\Controllers\ArticleController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;
    protected $fillable = [
        'content',
        'user_id',
        'article_id',
    ];

    public function article(){
        return $this->BelongsTo(Article::class);
    }
    
    public function user(){
        return $this->BelongsTo(User::class);
    }
   
}
