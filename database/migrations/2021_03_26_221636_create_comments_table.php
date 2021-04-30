<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'comments',
            function (Blueprint $table) {
                $table->id();
                $table->text("text");
                $table->bigInteger("user_id")->unsigned();
                $table->bigInteger('post_id')->unsigned();
                $table->bigInteger('user_reply_id')->nullable();
                $table->bigInteger('parent_id')->unsigned()->nullable(); // Самый первый комментарий в ветке

                $table->foreign("user_id")->references('id')->on('users');
                $table->foreign("post_id")->references('id')->on('posts');
                $table->foreign("parent_id")->references('id')->on('comments');

                $table->softDeletes();
                $table->timestamps();
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
        Schema::dropIfExists('comments');
    }
}
