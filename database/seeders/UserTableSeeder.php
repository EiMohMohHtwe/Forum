<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User();

        $user->name = "Jessica Alba ";
        $user->email = "test3@gmail.com";
        $user->password = bcrypt('33333333');
        $user->save();
    }
}
