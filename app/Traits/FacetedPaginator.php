<?php

namespace App\Traits;

use Illuminate\Pagination\LengthAwarePaginator;

class FacetedPaginator extends LengthAwarePaginator
{
    protected $facets;

    public function __construct($items, $total, $perPage, $currentPage = null, array $options = [], array $facets = [])
    {
        $this->facets = $facets;

        parent::__construct($items, $total, $perPage, $currentPage, $options);
    }

    public function facets(): array
    {
        return $this->facets;
    }

    public function toArray()
    {
        return [
            'current_page' => $this->currentPage(),
            'data' => $this->items->toArray(),
            'facets' => $this->facets(),
            'first_page_url' => $this->url(1),
            'from' => $this->firstItem(),
            'last_page' => $this->lastPage(),
            'last_page_url' => $this->url($this->lastPage()),
            'links' => $this->linkCollection()->toArray(),
            'next_page_url' => $this->nextPageUrl(),
            'path' => $this->path(),
            'per_page' => $this->perPage(),
            'prev_page_url' => $this->previousPageUrl(),
            'to' => $this->lastItem(),
            'total' => $this->total(),
        ];
    }
}
