<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'users',
            function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('login')->nullable(true);
                $table->string('email')->unique();
                $table->string('position')->nullable();
                $table->string('country')->nullable();
                $table->string('social_id')->nullable();
                $table->string('role')->default('user');
                $table->string('network')->nullable();
                $table->string('ip')->nullable();
                $table->string('avatar')->nullable();
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->text('description')->nullable();
                $table->tinyInteger('ready_for_work')->default(0);
                $table->boolean('recognized')->default(false);
                $table->boolean('is_new_comment_notification')->default(true);
                $table->boolean('is_reply_to_my_comment_notification')->default(true);
                $table->boolean('is_new_follower_notification')->default(true);
                $table->string('status')->default('active');
                $table->json('links')->nullable(true);
                $table->rememberToken();
                $table->timestamps();
                $table->softDeletes();
                $table->foreignId('current_team_id')->nullable();
                $table->string('profile_photo_path', 2048)->nullable();
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
        Schema::dropIfExists('users');
    }
}
