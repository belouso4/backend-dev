<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $superAdmin = Role::where('slug','super-admin')->first();
        $user1 = new User();
        $user1->name = 'Jhon Deo';
        $user1->email = 'jhon@deo.com';
        $user1->password = 'secret';
        $user1->save();
        $user1->roles()->attach($superAdmin);

//        User::factory()->count(30)->create();

        for ($i = 0; $i < 1000; $i++) {
            $user = UserFactory::new()->make();

            $data[] = [
                'name' => $user->name,
                'email' => $user->email,
                'email_verified_at' => now(),
                'password' => bcrypt('password'), // password
            ];
        }

        $chunks = array_chunk($data, 1000);

        foreach ($chunks as $chunk) {
            User::insert($chunk);
        }

    }
}
