<?php

namespace App\Models;

use App\Models\Traits\ActiveUserHelper;
use App\Models\Traits\LastActivedAtHelper;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use LastActivedAtHelper;

    use HasRoles;

    use ActiveUserHelper;

    use Notifiable {
        notify as protected laravelNotify;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'introduction',
        'avatar', 'phone', 'weixin_openid', 'weixin_unionid'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function isAuthOf($model)
    {
        return $this->id == $model->user_id;
    }

    public function setPasswordAttribute($value)
    {
        // 如果值的长度等于 60，即认为是已经做过加密的情况
        if (strlen($value) != 60) {
            // 不等于 60，做密码加密处理
            $value = bcrypt($value);
        }
        $this->attributes['password'] = $value;
    }


    public function notify($instance)
    {
        if ($this->id == Auth::id()){
            return;
        }

        if (method_exists($instance, 'toDatabase')){
            $this->increment('notification_count');
        }

        $this->laravelNotify($instance);
    }

    public function setAvatarAttribute($path)
    {
        if (!str_starts_with($path, 'http')) {
            $path = config('app.url') . "/uploads/images/avatars/$path";

        }
        $this->attributes['avatar'] = $path;
    }


    public static function defaultAvatar()
    {
        return trim(env('APP_URL'), '/') . '/uploads/images/default/default_header.png';
    }

    public function markAsRead()
    {
        $this->notification_count=0;
        $this->save();
        $this->unreadNotifications->markAsRead();
    }


}
