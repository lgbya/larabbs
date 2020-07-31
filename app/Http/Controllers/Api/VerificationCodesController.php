<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\VerificationCodeRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Overtrue\EasySms\EasySms;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;

class VerificationCodesController extends Controller
{
    //
    public function store(VerificationCodeRequest $request, EasySms $easySms)
    {


        $phone = $request->phone;
        if(!app()->environment('production')){
            $code = '1234';
        }else{

            $code = str_pad(random_int(1, 999), 4, 0 , STR_PAD_LEFT);

            try{
                $result = $easySms->send($phone, [
                    'content' => "【Lbbs社区】您的验证码是{$code}",
                ]);
            }catch (NoGatewayAvailableException $exception){
                $message = $exception->getException('yunpian')->getMessage();
                return $this->response->errorInternal($message ?? '短信发送异常');
            }

        }


        $key = 'verificationCode_' . Str::random(15);
        $expiredAt = now()->addMinutes(10);

        Cache::put($key, ['phone'=>$phone, 'code' => $code], $expiredAt);

        return $this->response->array([
            'key' => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
        ])->setStatusCode(201);
    }
}
