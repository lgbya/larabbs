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
        $faker = app(Generator::class);

        $avatars = [
            'http://larabbs.test/uploads/images/avatars/202007/29/1_1595958035_QfnGaP5bsm.jpg',
        ];

        $users = factory(User::class)
            ->times(10)
            ->make()
            ->each(function ($user, $index) use ($faker, $avatars){
                $user->avatar = $faker->randomElement($avatars);
            });

        $userArray = $users->makeVisible(['password', 'remember_token'])->toArray();

        // 插入到数据库中
        User::insert($userArray);
        // 单独处理第一个用户的数据
        $user = User::find(1);
        $user->name = 'Summer';
        $user->email = 'summer@example.com';
        $user->avatar = 'https://iocaffcdn.phphub.org/uploads/images/201710/14/1/ZqM7iaP4CR.png';
        $user->save();

    }
}
