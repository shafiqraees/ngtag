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
    public function tag_name($term){
        return $this->builder->where('tag_name', $term);
    }
    public function tag_no($term){
        return $this->builder->where('tag_no', $term);
    }
    public function tag_type($term){
        return $this->builder->where('tag_type', $term);
    }

    public function account_id($term){
        return $this->builder->where('account_id', $term);
    }
    public function tag_digits($term){
        return $this->builder->where('tag_digits', $term);
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
