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

        return ImageFile::facetedSearch(request('query', ''), [
            'resolution' => request('resolutions'),
        ])
            ->where('user_id', auth()->user()->id)
            ->paginate();
    }
}
