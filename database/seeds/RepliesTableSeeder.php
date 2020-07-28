<?php

use Illuminate\Database\Seeder;
use App\Models\Reply;
use App\Models\User;
use App\Models\Topic;
use Faker\Generator;

class RepliesTableSeeder extends Seeder
{
    public function run()
    {
        $userIds = User::all()->pluck('id')->toArray();

        $topicIds = Topic::all()->pluck('id')->toArray();

        $factory = app(Generator::class);

        $replies = factory(Reply::class)
            ->times(50)
            ->make()
            ->each(function ($reply, $index) use ($factory,$userIds, $topicIds){
                $reply->user_id = $factory->randomElement($userIds);
                $reply->topic_id = $factory->randomElement($topicIds);
            });

        Reply::insert($replies->toArray());
    }

}

