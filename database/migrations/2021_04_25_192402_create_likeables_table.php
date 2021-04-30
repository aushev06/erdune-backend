<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLikeablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'likeables',
            function (Blueprint $table) {
                $table->id();
                $table->string('likeable_type')->index();
                $table->integer('likeable_id')->index();
                $table->bigInteger('user_id')->unsigned();
                $table->enum('type', ['like', 'dislike'])->index();
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('likeables');
    }
}
