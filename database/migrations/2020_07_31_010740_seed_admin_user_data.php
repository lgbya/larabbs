<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

class SeedAdminUserData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $users = factory(User::class)->times(1)->make();
        $userArray = $users->makeVisible(['password', 'remember_token'])->toArray();

        // 插入到数据库中
        User::insert($userArray);
        // 单独处理第一个用户的数据
        $user = User::find(1);
        $user->name = 'Admin';
        $user->email = 'admin@admin.com';
        $user->save();
        $user->assignRole('Founder');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
