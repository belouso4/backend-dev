<?php

namespace Database\Factories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Log;
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
        $tag_name = $this->getTag();

        $tag = [
            'tag' => $tag_name,
        ];

        $tag['slug'] = Str::slug($tag_name);
        $check = Tag::where('slug', '=', $tag['slug'])->exists();

        if ($check) {
            $tag['slug'] = Str::slug($tag['slug']) . time();
        }

        return $tag;
    }

    public function getTag() {
        $text = $this->faker->word;

        $check = Tag::where('tag', '=', $text)->exists();

        if ($check) {
            return Str::slug($text) . time();
        } else {
            return $text;
        }
    }
}
