<?php

namespace App\Traits;

use Algolia\ScoutExtended\Facades\Algolia;
use Illuminate\Support\Arr;

class FacetedBuilder
{
    protected $builder;
    protected $facets;
    protected $searches;

    public function __construct($model, $query, $facets, $searches)
    {
        $this->facets = collect($facets);
        $this->searches = collect($searches);

        $this->builder = $model::search($query, function ($algolia, $query, $options) {
            $this->searches = $this->searches
                ->map(function ($search, $facet) use ($algolia, $query) {
                    $result = $algolia->searchForFacetValues($facet, $search, [
                        'query' => $query,
                        'facetFilters' => $this->getFacetFilters($facet),
                    ]);

                    return collect(Arr::get($result, 'facetHits'))
                        ->mapWithKeys(function ($facet, $key) {
                            return [$facet['value'] => $facet['count']];
                        })
                        ->toArray();
                });

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
        $query['facetFilters'] = $this->getFacetFilters($facet);
        return $query;
    }

    private function getFacetFilters($facet = '*')
    {
        return $this->facets
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
    }

    private function getFacets()
    {
         $facets = collect($this->results->first()['facets'])
            ->map(function ($values, $facet) {
                return collect($values)->take(10)->toArray();
            })
            ->toArray();

        $this->results->skip(1)
            ->each(function ($result) use (&$facets) {
                collect($result['facets'])
                    ->each(function ($values, $facet) use (&$facets) {
                        $facets[$facet] = collect($values)->take(10)->toArray();
                    });
            });

        $this->searches
            ->each(function ($values, $facet) use (&$facets) {
                $facets[$facet] = $this->facets->has($facet) ? $values + $this->results->first()['facets'][$facet] : $values;
            });

        return $facets;
    }
}
