<?php

namespace App\Traits;

trait Faceted
{
    public static function facetedSearch($query = '', $callback = null, $facetFilters = [])
    {
        return new FacetedBuilder($query, $callback, $facetFilters);
    }
}
