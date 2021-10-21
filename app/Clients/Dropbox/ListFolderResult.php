<?php

namespace App\Clients\Dropbox;

use Spatie\Dropbox\Client;

class ListFolderResult
{
    protected $entires;
    protected $cursor;
    protected $has_more;

    public function __construct($data)
    {
        $this->entries = $data['entries'];
        $this->cursor = $data['cursor'];
        $this->has_more = $data['has_more'];
    }

    public function entries()
    {
        return collect($this->entries)
            ->map(function ($entry) {
                return new Metadata($entry['.tag'], $entry['path_lower']);
            });
    }

    public function cursor()
    {
        return $this->cursor;
    }

    public function hasMore()
    {
        return $this->has_more;
    }
}
