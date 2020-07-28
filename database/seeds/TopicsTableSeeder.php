<?php

use Illuminate\Database\Seeder;
use App\Models\Topic;
use App\Models\User;
use App\Models\Category;
use Faker\Generator;

class TopicsTableSeeder extends Seeder
{
    public function run()
    {

        $userIds = User::all()->pluck('id')->toArray();

        $categoryIds = Category::all()->pluck('id')->toArray();

        $faker = app(Generator::class);

        $topics = factory(Topic::class)
            ->times(10)
            ->make()
            ->each(function ($topic, $index) use ($userIds, $categoryIds, $faker) {
                $topic->user_id = $faker->randomElement($userIds);
                $topic->category_id = $faker->randomElement($categoryIds);
            });
        Topic::insert($topics->toArray());
        $topic = Topic::find(1);
        $topic->user_id = 1;
        $topic->save();
    }

}

