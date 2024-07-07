<?php

namespace App\Traits;

trait HasFilters
{
    public function scopeFilter($builder, $filters, $used_as = 'object'){
        if( !empty($filters) ) {
            $filters->used_as = $used_as;
            return $filters->apply($builder);
            /*$filter_parent_class = get_parent_class($filters);
            if( ($filters instanceof QueryFilterBase) || ($filter_parent_class === "App\Filters\QueryFilterBase") ){
                return $filters->apply($query);
            }*/
        }
        return $builder;
    }
}
