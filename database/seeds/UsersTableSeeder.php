<?php

use Illuminate\Database\Seeder;
use Faker\Generator;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $users = factory(User::class)
            ->times(10)
            ->make();

        $userArray = $users->makeVisible(['password', 'remember_token'])->toArray();

        // 插入到数据库中
        User::insert($userArray);
        $user = User::find(2);
        $user->assignRole('Maintainer');

    }
}
