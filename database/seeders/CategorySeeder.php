<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'name' => 'ИИ',
                'slug' => 'ii',
                'order' => 1,
            ],
            [
                'name' => 'Смартфоны',
                'order' => 2,
                'slug' => 'smartphones',
            ],
            [
                'name' => 'Бытовая техника',
                'order' => 3,
                'slug' => 'appliances',
            ],
            [
                'name' => 'chatGPT',
                'order' => 4,
                'slug' => 'chatgpt',
                'parent_id' => '1',
            ],
          ];


//        $category->fill($categories)->save();


        for ($i = 0; $i < count($categories); $i++) {
            $category = new Category();

            $category->fill($categories[$i])->save();
//            Category::create(['name' => $categories[$i]['name'], 'slug' => $categories[$i]['name'],'parent_id' => $categories[$i]['parent_id'] ?? null]);
        }
    }
}
