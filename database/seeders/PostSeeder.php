<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Database\Factories\PostFactory;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
//        $post = PostFactory::new()->make();
        $start = microtime(true);

        for ($i = 0; $i < 1000; $i++) {
            $data[] = [
                'title' => $faker->realTextBetween(10, 30, 3),
                'category_id' => Category::all()->random()->id,
                'excerpt' => $faker->text( 50 ),
                'desc' => $faker->realText( 100 ),
                'img' => '300x200.png',
                'slug' => $faker->uuid(),
                'created_at' => now()->addMinutes(10 + $i),
                'updated_at' => now()->addMinutes(10 + $i),
            ];
        }

        $chunks = array_chunk($data, 500);

        foreach ($chunks as $chunk) {
            Post::insert($chunk);
        }

        foreach (Post::cursor() as $post) {
            $tags = Tag::inRandomOrder()->limit(random_int(2, 5))->get(['id']);
            $post->tags()->attach($tags);
        }

        $time_elapsed_secs = microtime(true) - $start;
        \Log::info($time_elapsed_secs);
    }
}
