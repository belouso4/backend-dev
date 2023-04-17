<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//
//        $max = 20;
//        for($c=1; $c<=$max; $c++) {
//            Tag::factory()->create();
//        }
        Tag::factory()->count(20)->create();
    }
}
