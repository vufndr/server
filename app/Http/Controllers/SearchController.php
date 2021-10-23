<?php

namespace App\Http\Controllers;

use App\Models\ImageFile;

class SearchController extends Controller
{
    public function index()
    {
        request()->validate([
            'query' => 'nullable|string|max:255',
        ]);

        return ImageFile::search(request('query', ''))
            ->where('user_id', auth()->user()->id)
            ->with(['facets' => ['*']])
            ->paginate();
    }
}
