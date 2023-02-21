<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable(false);
            $table->string('description')->nullable(false);
            $table->string('content')->nullable(false);
            $table->Integer('user_id')->unsigned()->index()->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->bigInteger('category_id')->unsigned()->index()->nullable();
            $table->foreign('category_id')->references('id')->on('categories');
            $table->bigInteger('tag_id')->unsigned()->index()->nullable();
            $table->foreign('tag_id')->references('id')->on('tags');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
};
