<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reply;

class ReplyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $reply = new Reply();

        $reply->thread_id = 7;
        $reply->user_id = 3;
        $reply->body = "During the research phase, we'll set up a brand new throw-away project to learn how it will work. ";
        $reply->save();

    }
}
