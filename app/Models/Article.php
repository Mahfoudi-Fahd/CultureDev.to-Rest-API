<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    use HasFactory;
    protected $fillable=['title', 'description', 'content','user_id', 'category_id', 'tag_id'];

    public function category(){
        return $this->BelongsTo(Category::class,'category_id','id')->select(['id', 'name']);
    }

    public function tag(){
        return $this->belongsToMany(Tag::class);
    }

    // public function user(){
    //     return $this->hasOne(user::class);
    // }

}
