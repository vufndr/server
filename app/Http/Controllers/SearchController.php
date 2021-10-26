<?php

namespace App\Http\Controllers;

use App\Models\ImageFile;

class SearchController extends Controller
{
    public function index()
    {
        request()->validate([
            'query' => 'nullable|string|max:255',
            'resolutions' => 'array',
        ]);

        $facetFilters = [];

        if (request()->has('resolutions')) {
            $facetFilters[] = collect(request('resolutions'))
                ->map(function ($resolution) {
                    return 'resolution:' . $resolution;
                })
                ->toArray();
        }

        return ImageFile::facetedSearch(request('query', ''), $facetFilters)
            ->where('user_id', auth()->user()->id)
            ->paginate();
    }
}
