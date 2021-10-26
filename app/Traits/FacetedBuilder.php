<?php

namespace App\Traits;

class FacetedBuilder
{
    protected $builder;
    protected $facetFilters;

    public function __contstruct($query, $callback, $facetFilters)
    {
        $this->builder = static::search($query, function ($algolia, $query, $options) {
            dump([$algolia, $query, $options]);
            return $algolia->search($query, $options);
        });
        $this->facetFilters = $facetFilters;
    }

    public function paginate($perPage = null, $pageName = 'page', $page = null)
    {
        return $this->builder->paginate($perPage, $pageName, $page);
    }

    public function __call($method, $parameters)
    {
        $this->builder->{$method}(...$parameters);

        return $this;
    }
}
