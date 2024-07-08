<?php

namespace App\Repositories;

use App\Filters\QueryFilterBase;
use App\Models\CorpTagList;
use App\Models\VoiceMail;
use App\Traits\TaggedCache;
use Throwable;

class VoiceMailRepository
{
    use TaggedCache;

    /**
     * @var string
     */
    public $cache_tag = 'CorpTagList';

    /**
     * @param QueryFilterBase|null $filters
     * @return \Illuminate\Database\Eloquent\Collection|mixed
     * @throws Throwable
     */
    public function index(QueryFilterBase $filters = null)
    {
        try {

            $limit = request()->get('limit', config('cache.per_page'));
            return VoiceMail::filter($filters)->paginate($limit);
            if (!empty($filters) && $filters->hasFilters()) {
                return CorpTagList::paginate($limit);
            }
            $key = "{$this->cache_tag}";
            if ($this->hasCache($this->cache_tag, $key)) {
                return $this->getCache($this->cache_tag, $key);
            } else {
                $results = CorpTagList::filter($filters)->paginate($limit);
                return $this->storeCache($this->cache_tag, $key, $results, config('cache.duration'));
            }
        } catch (Throwable $exception) {

            report($exception);
            throw $exception;
        }
    }
}
