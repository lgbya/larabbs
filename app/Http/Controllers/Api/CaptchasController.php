<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\CaptchaRequest;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CaptchasController extends Controller
{
    public function store(CaptchaRequest $request, CaptchaBuilder $captchaBuilder)
    {
        $key = 'captcha-' . Str::random(15);

        $phone = $request->phone;

        $captcha = $captchaBuilder->build();

        $expiredAt = now()->addMinutes(2);

        Cache::put($key, ['phone'=>$phone, 'captcha'=>$captcha->getPhrase()], $expiredAt);

        $relust = [
            'captcha_key' => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
            'captcha_image_content' => $captcha->inline(),
        ];

        return $this->response->array($relust)->setStatusCode(201);
    }
}
