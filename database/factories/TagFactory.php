<?php

namespace Database\Factories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TagFactory extends Factory
{

    protected $model = Tag::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $text = $this->faker->word;

        $tag = [
            'tag' => $text,
        ];

        $tag['slug'] = Str::slug($text);
        $check = Tag::where('slug', '=', $tag['slug'])->exists();

        if ($check) {
            $tag['slug'] = Str::slug($tag['slug']) . time();
        }

        return $tag;
    }
}
