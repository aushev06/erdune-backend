<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Theme;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Post::factory()->count(50)->create();
        Comment::factory()->count(300)->create();


        $themes = ['разработка', 'советы', 'дизайн', 'инструкции', 'mvp', 'проектирование', 'инструментарий', 'болтология', 'работа'];

        foreach ($themes as $theme) {
            Theme::query()->create(['name' => implode('#', ['', $theme])]);
        }

        $posts = Post::all();

        $posts->each(function (Post $post) {
            $themes = Theme::query()->inRandomOrder()->limit(5)->get();
            $post->themes()->sync($themes->map(fn(Theme $theme) => $theme->id));
        });




    }
}
