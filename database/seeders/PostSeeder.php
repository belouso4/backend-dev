<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $posts = Post::factory()->count(30)->create();

        foreach ($posts as $post) {
            $tags = Tag::inRandomOrder()->limit(random_int(2, 5))->get(['id']);
            $post->tags()->attach($tags);
        }
    }
}
