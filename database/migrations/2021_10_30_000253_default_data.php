<?php

use App\Models\Theme;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DefaultData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $themes = ['разработка', 'советы', 'дизайн', 'инструкции', 'mvp', 'проектирование', 'инструментарий', 'болтология', 'работа'];

        foreach ($themes as $theme) {
            Theme::query()->create(['name' => implode('#', ['', $theme])]);
        }


        $categories = [
            ['name' => 'Dev Battle', 'slug' => 'dev-battle'],
            ['name' => 'Design Battle', 'slug' => 'design-battle'],
            ['name' => 'Design Review', 'slug' => 'design-review'],
            ['name' => 'Dev Review', 'slug' => 'dev-review'],
            ['name' => 'Дизайн за чаем', 'slug' => 'design-over-tea'],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::query()->insert($category);
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
