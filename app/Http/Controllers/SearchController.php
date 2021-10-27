<?php

namespace App\Http\Controllers;

use App\Models\ImageFile;

class SearchController extends Controller
{
    public function index()
    {
        request()->validate([
            'query' => 'nullable|string',
            'facets' => 'nullable|array',
            'searches' => 'nullable|array',
        ]);

        return ImageFile::facetedSearch(request('query', ''), request('facets', []))
            ->where('user_id', auth()->user()->id)
            ->paginate();
    }
}
