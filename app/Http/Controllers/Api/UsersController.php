<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function store(UserRequest $request)
    {
        $verifyDate = Cache::get($request->verification_key);


        if(!$verifyDate){
            return $this->response->error('验证码已失效', 422);
        }

        if (!hash_equals($verifyDate['code'], $request->verification_code)){
            return $this->response->errorUnauthorized('验证码错误');
        }

        $user = User::create([
            'name' => $request->name,
            'phone' => $verifyDate['phone'],
            'password'=>bcrypt($request->password),
        ]);

        Cache::forget($request->verification_key);

        return $this->response->created();
    }
}
