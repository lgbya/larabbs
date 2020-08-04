<?php

namespace App\Models\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;

trait LastActivedAtHelper
{
    // 缓存相关
    protected $hash_prefix = 'larabbs_last_actived_at_';
    protected $field_prefix = 'user_';

    public function recordLastActivedAt()
    {
        $date = Carbon::now()->toDateString();

        $hash = $this->getHashFromDateString($date);

        $field = $this->getHashField();

        $now = Carbon::now()->toDateString();

        Redis::hSet($hash, $field, $now);
    }

    public function syncUserActivedAt()
    {
        $yesterdayDate = Carbon::yesterday()->toDateString();

        $hash = $this->getHashFromDateString($yesterdayDate);

        $dates = Redis::hGetAll($hash);

        foreach($dates as $userId => $activedAt){
            $userId = str_replace($this->field_prefix, '', $userId);

            if($user = $this->find($userId)){
                $user->last_actived_at = $activedAt;
                $user->save();
            }
        }

        Redis::del($hash);
    }

    public function getLastActivedAtAttribute($value)
    {
        $date = Carbon::now()->toDateString();

        $hash = $this->getHashFromDateString($date);

        $field = $this->getHashField();

        $dateTime = Redis::hGet($hash, $field) ?: $value;

        if($dateTime){
            return new Carbon($dateTime);
        } else {
            return $this->created_at;
        }

    }

    public function getHashFromDateString($date)
    {
        // Redis 哈希表的命名，如：larabbs_last_actived_at_2017-10-21
        return $this->hash_prefix . $date;
    }
    public function getHashField()
    {
        // 字段名称，如：user_1
        return $this->field_prefix . $this->id;
    }
}
