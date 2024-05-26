<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait TaggedCache
{
    public function storeCache(string|array $tags, string $key, mixed $data, $duration_in_seconds){
        Cache::tags($tags)->put($key, $data, $duration_in_seconds);
        return $data;
    }
    public function getCache(string|array $tags, string $key){
        return Cache::tags($tags)->get($key);
    }
    public function hasCache(string|array $tags, string $key){
        return Cache::tags($tags)->has($key);
    }
    public function clearCache(string|array $tags, string $key = null){
        if( !empty($key) ){
            Cache::tags($tags)->forget($key);
        }
        else{
            Cache::tags($tags)->flush();
        }
        return true;
    }

    public function clearSelectedCache($key) {
        return Cache::forget($key);
    }
}
