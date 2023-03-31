<?php


namespace Database\Factories;

use App\Models\Post;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->realTextBetween(10, 30, 3),
//            'subscription' => 0,
            'excerpt' => $this->faker->text( 120 ),
            'desc' => $this->faker->realText( 2000 ),
            'img' => '300x200.png',
        ];
    }

//    public function suspended()
//    {
//        return $this->state(function (array $attributes) {
//            return [
//                'meta_keywords' => 'owner',
//            ];
//        });
//    }

}

