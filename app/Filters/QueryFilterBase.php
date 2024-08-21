<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use ReflectionMethod;

class QueryFilterBase
{
    protected Request $request;
    protected Builder $builder;
    public string $used_as = 'object';
    protected bool $hasDates = false;
    protected bool $fuzzy = true;
    protected array $forbidden_filters = ['no_cache', 'trimPhoneNumber'];
    protected array $raw_filters = ['currency'];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function hasApplicableFilters(): bool
    {
        return $this->hasFilters(true);
    }

    public function __call($method, $args)
    {
        if (method_exists($this, $method)) {
            return call_user_func_array([$this, $method], $args);
        }
        return null;
    }

    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;
        foreach ($this->request->all() as $name => $value) {
            if (in_array($name, $this->forbidden_filters)) {
                continue;
            }

            if ($this->used_as === 'raw' && in_array($name, $this->raw_filters)) {
                $name .= '_raw';
            }

            if (method_exists($this, $name)) {
                $this->applyFilterMethod($name, $value);
            }
        }
        return $this->builder;
    }

    protected function applyFilterMethod(string $name, $value): void
    {
        $reflection = new ReflectionMethod($this, $name);
        if (!empty($reflection->getParameters()) && isset($value)) {
            $this->builder = $this->$name($value);
        } elseif (empty($reflection->getParameters())) {
            $this->builder = $this->$name();
        }
    }

    public function hasFilters(bool $skip_forbidden = false): bool
    {
        return $this->getFilters($skip_forbidden)['status'];
    }

    public function getFilters(bool $skip_forbidden = false): array
    {
        $filters = [];
        foreach ($this->request->all() as $name => $value) {
            if ($skip_forbidden && in_array($name, $this->forbidden_filters)) {
                continue;
            }
            if (method_exists($this, $name)) {
                $filters[$name] = $value;
            }
        }

        return [
            'filters' => $filters,
            'status' => !empty($filters),
        ];
    }

    protected function no_cache(): Builder
    {
        return $this->builder;
    }

    protected function trimPhoneNumber(string $term): string
    {
        if (str_starts_with($term, '44')) {
            $term = substr($term, 2);
        }
        return $term;
    }

    public function hasDateFilters(): bool
    {
        return $this->hasDates;
    }

    public function hasOnly(string|array $filter_name): bool
    {
        $filter_name = Arr::wrap($filter_name);
        $filters = $this->getFilters(true)['filters'];

        return empty(array_diff(array_keys($filters), $filter_name));
    }

    public function has(string|array $filter_name): bool
    {
        $filter_name = Arr::wrap($filter_name);
        $filters = $this->getFilters(true)['filters'];

        return !empty(array_intersect(array_keys($filters), $filter_name));
    }
}
