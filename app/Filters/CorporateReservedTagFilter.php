<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class CorporateReservedTagFilter extends QueryFilterBase
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
    public function corp_customer_account_id($term){
        return $this->builder->where('corp_customer_account_id', $term);
    }
    public function msisdn($term){
        return $this->builder->where('msisdn', $term);
    }
    public function phone_number($term){
        return $this->builder->where('phone_number', $term);
    }
    public function corp_tag_list_id($term){
        return $this->builder->where('corp_tag_list_id', $term);
    }
    public function payment_method($term){
        return $this->builder->where('payment_method', $term);
    }
    public function payment_status($term){
        return $this->builder->where('payment_status', $term);
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
                ->OrWhere('corp_customer_account_id', 'LIKE', "%$term%")
                ->OrWhere('phone_number', 'LIKE', "%$term%")
                ->OrWhere('msisdn', 'LIKE', "%$term%")
                ->OrWhere('corp_tag_list_id', 'LIKE', "%$term%");
        });
    }
}
