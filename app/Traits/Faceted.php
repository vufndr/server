<?php

namespace App\Traits;

trait Faceted
{
    public static function facetedSearch($query = '', $callback = null, $facetFilters = [])
    {
        return new FacetedBuilder(static::class, $query, $callback, $facetFilters);
    }
}
