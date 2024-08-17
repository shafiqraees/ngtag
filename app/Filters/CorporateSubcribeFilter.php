<?php

namespace App\Filters;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class CorporateSubcribeFilter extends QueryFilterBase
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
        return $this->builder->where('account_id', $term);
    }
    public function msisdn($term){
        return $this->builder->where('msisdn', $term);
    }
    public function tag_id($term){
        return $this->builder->where('tag_id', $term);
    }
    public function name_tag($term){
        return $this->builder->where('name_tag', $term);
    }
    public function tag_no($term){
        return $this->builder->where('tag_no', $term);
    }
    public function tag_type($term){
        return $this->builder->where('tag_type', $term);
    }
    public function tag_length($term){
        return $this->builder->where('tag_length', $term);
    }
    public function tag_no_price($term){
        return $this->builder->where('tag_no_price', $term);
    }
    public function payment_method($term){
        return $this->builder->where('payment_method', $term);
    }
    public function payment_status($term){
        return $this->builder->where('payment_status', $term);
    }
    public function payment_date($term){
        return $this->builder->where('payment_date', $term);
    }
    public function expiry_date($term){
        return $this->builder->where('expiry_date', $term);
    }
    public function service_fee($term){
        return $this->builder->where('service_fee', $term);
    }
    public function sub_date($term){
        return $this->builder->where('sub_date', $term);
    }
    public function unsub_date($term){
        return $this->builder->where('unsub_date', $term);
    }
    public function unsub_channel($term){
        return $this->builder->where('unsub_channel', $term);
    }
    public function charge_dt($term){
        return $this->builder->where('charge_dt', $term);
    }
    public function next_charge_dt($term){
        return $this->builder->where('next_charge_dt', $term);
    }
    public function voic_email($term){
        return $this->builder->where('voic_email', $term);
    }
    public function incall_start_dt($term){
        return $this->builder->where('incall_start_dt', $term);
    }
    public function incall_end_dt($term){
        return $this->builder->where('incall_end_dt', $term);
    }
    public function status($term){
        return $this->builder->where('status', $term);
    }
    public function service_id($term){
        return $this->builder->where('service_id', $term);
    }
    public function incoming_call_status($term){
        return $this->builder->where('incoming_call_status', $term);
    }
    public function corp_subscriber_id($term){
        return $this->builder->where('corp_subscriber_id', $term);
    }
    public function service_status($term){
        return $this->builder->where('service_status', $term);
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
