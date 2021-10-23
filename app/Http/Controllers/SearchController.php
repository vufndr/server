<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ImageFile;
use Exception;

class SearchController extends Controller
{
    public function index()
    {
        $images = ImageFile::search()
            ->where('user_id', auth()->user()->id)
            ->with(['facets' => ['*']])
            ->paginate();
    }
}
