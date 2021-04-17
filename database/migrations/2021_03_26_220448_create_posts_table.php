<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'posts',
            function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->json('body');
                $table->text('description');
                $table->string('img')->nullable();
                $table->string('slug')->unique();
                $table->integer('views')->default(0);
                $table->string('meta_description')->nullable();
                $table->string('meta_keywords')->nullable();
                $table->string('status')->default('pending')->index();
                $table->integer('likes')->default(0);
                $table->integer('dislikes')->default(0);

                $table->foreign('user_id')->references('id')->on('users');
                $table->bigInteger('user_id')->unsigned();

                $table->timestamps();
                $table->softDeletes();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
