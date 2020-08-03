<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\SocialAuthorizationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AuthorizationsController extends Controller
{
    public function socialStore($type, SocialAuthorizationRequest $request)
    {
        if (!in_array($type, ['weixin'])){
            return $this->response->errorBadRequest();
        }

        $driver = Socialite::driver($type);

        try{
            if(isset($request->code)){

                $response = $driver->getAccessTokenResponse($request->code);
                $token = Arr::get($response, 'access_token');
            } else {
                $token = $request->access_token;

                if($type == 'weixin'){
                    $driver->setOpenId($request->openid);
                }
            }
            $oauthUser = $driver->userFromToken($token);

        }catch (\Exception $e){
            return $this->response->errorUnauthorized('参数错误，未获取用户信息');
        }

        switch ($type) {
            case 'weixin' :
                $unionid = $oauthUser->offsetExists('unionid') ? $oauthUser->offsetGet('unionid') : '';

                if($unionid){
                    $user = User::where('weixin_unionid', $unionid)->first();
                } else {
                    $user = User::where('weixin_openid', $oauthUser->getId())->first();
                }

                if(!$user){
                    $user = User::create([
                        'name' => $oauthUser->getNickName(),
                        'avatar' => $oauthUser->getAvatar(),
                        'weixin_openid' => $oauthUser->getId(),
                        'weixin_unionid' => $unionid,
                    ]);
                }

                break;

        }

        return $this->response->array(['token' => $user->id])->setStatusCode(201);
    }
}
