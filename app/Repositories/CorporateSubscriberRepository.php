<?php

namespace App\Repositories;

use App\Filters\QueryFilterBase;
use App\Models\AdminPortal;
use App\Models\CorpCustomerAccount;
use App\Models\CorpSubscriber;
use App\Models\CorpTagList;
use App\Traits\TaggedCache;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Throwable;

class CorporateSubscriberRepository
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
            return CorpSubscriber::filter($filters)->paginate($limit);
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

    public function updateSubscriber(array $data, CorpSubscriber $account) {
        try {
            $account->incoming_call_status = $data['incoming_call_status'] ?? $account->incoming_call_status;
            $account->incall_end_dt = $data['incall_end_dt'] ?? $account->incall_end_dt;
            $account->incall_start_dt = $data['incall_start_dt'] ?? $account->incall_start_dt;
            $account->voic_email = $data['voic_email'] ?? $account->voic_email;
            $account->service_status = $data['service'] ?? $account->service_status;
            $account->save();
            return $account;
        } catch (\Exception $exception ) {
            report($exception);
            throw $exception;
        }
    }
}
