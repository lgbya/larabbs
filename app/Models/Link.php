<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class Link extends Model
{
    const CACHE_KEY = 'larabbs_links';

    const CACHE_EXPIRE_IN_MINUTES = 1440;

    protected $fillable = ['title', 'link'];

    public function getAllCached()
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_EXPIRE_IN_MINUTES, function (){
           return $this->all();
        });
    }



}
