<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'parent_id'     => null,
            'text'          => $this->faker->text,
            'post_id'       => Post::query()->inRandomOrder()->first()->id,
            'user_id'       => User::query()->inRandomOrder()->first()->id,
            'user_reply_id' => null,
        ];
    }

}
