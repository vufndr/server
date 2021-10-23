<?php

namespace App\Http\Controllers;

use App\Models\ImageFile;

class SearchController extends Controller
{
    public function index()
    {
        request()->validate([
            'query' => 'nullable|string|max:255',
            'resolution' => 'nullable|string|max:255',
        ]);

        $facetFilters = [];

        if (request()->has('resolution')) {
            $facetFilters['resolution'] = request('resolution');
        }

        return ImageFile::search(request('query', ''))
            ->where('user_id', auth()->user()->id)
            ->with([
                'facets' => ['*'],
                'facetFilters' => $facetFilters,
            ])
            ->paginate();
    }
}
