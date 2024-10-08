<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class VoiceMailFilter extends QueryFilterBase
{
    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        parent::__construct($request);
    }

    /**
     * @param $term
     * @return mixed
     */
    public function id($term){
        return $this->builder->where('id', $term);
    }
    public function account_id($term){
        return $this->builder->where('corp_customer_account_id', $term);
    }
    public function msisdn($term){
        return $this->builder->where('msisdn', $term);
    }
    public function caller_no($term){
        return $this->builder->where('caller_no', $term);
    }
    public function user_type($term){
        return $this->builder->where('user_type', $term);
    }
    public function status($term){
        return $this->builder->where('status', $term);
    }


    /**
     * @param $term
     * @return mixed
     */
    public function search($term) {
        return $this->builder->where(function($query) use($term){
            $query->where('id', 'LIKE', "%$term%")
                ->OrWhere('tag_name', 'LIKE', "%$term%")
                ->OrWhere('tag_no', 'LIKE', "%$term%")
                ->OrWhere('tag_type', 'LIKE', "%$term%")
                ->OrWhere('tag_price', 'LIKE', "%$term%")
                ->OrWhere('service_fee', 'LIKE', "%$term%")
                ->OrWhere('status', 'LIKE', "%$term%");
        });
    }
}
