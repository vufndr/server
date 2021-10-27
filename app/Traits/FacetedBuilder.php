<?php

namespace App\Traits;

use Algolia\ScoutExtended\Facades\Algolia;
use Illuminate\Support\Arr;

class FacetedBuilder
{
    protected $builder;
    protected $facets;

    public function __construct($model, $query, $facets)
    {
        $this->facets = collect($facets);

        $this->builder = $model::search($query, function ($algolia, $query, $options) {
            $queries = $this->getQueries($algolia, $query, $options);
            $this->results = collect(Arr::get(Algolia::client()->multipleQueries($queries), 'results', []));
            return $this->results->first();
        });
    }

    public function paginate($perPage = null, $pageName = 'page', $page = null)
    {
        $result = $this->builder->paginate($perPage, $pageName, $page);

        return new FacetedPaginator(
            $result->getCollection(),
            $result->total(),
            $result->perPage(),
            $result->currentPage(),
            $result->getOptions(),
            $this->getFacets()
        );
    }

    public function __call($method, $parameters)
    {
        $this->builder->{$method}(...$parameters);

        return $this;
    }

    private function getQueries($algolia, $query, $options)
    {
        $query = [
            'indexName' => $algolia->getIndexName(),
            'query' => $query,
        ] + $options;

        $queries = collect([$this->getQuery($query)]);

        $this->facets
            ->keys()
            ->each(function ($facet) use ($query, $queries) {
                $queries->add($this->getQuery($query, $facet));
            });

        return $queries->toArray();
    }

    private function getQuery($query, $facet = '*')
    {
        $query['facets'] = [$facet];

        $query['facetFilters'] = $this->facets
            ->filter(function ($values, $name) use ($facet) {
                return $name !== $facet;
            })
            ->map(function ($values, $name) {
                return collect($values)
                    ->map(function ($value) use ($name) {
                        return $name . ':' . $value;
                    })
                    ->values()
                    ->toArray();
            })
            ->values()
            ->toArray();

        return $query;
    }

    private function getFacets()
    {
        $facets = $this->results->first()['facets'];

        $this->results->skip(1)
            ->each(function ($result) use (&$facets) {
                collect($result['facets'])
                    ->each(function ($values, $facet) use (&$facets) {
                        $facets[$facet] = $values;
                    });
            });

        return $facets;
    }
}
