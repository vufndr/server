<?php

namespace App\Traits;

trait Faceted
{
    public static function facetedSearch($query = '', $facetFilters = [])
    {
        return new FacetedBuilder(static::class, $query, $facetFilters);
    }
}
