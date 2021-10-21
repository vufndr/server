<?php

namespace App\Clients\Dropbox;

use Spatie\Dropbox\Client;

class Metadata
{
    protected $type;
    protected $path;

    public function __construct($type, $path)
    {
        $this->type = $type;
        $this->path = $path;
    }

    public function type()
    {
        return $this->type;
    }

    public function path()
    {
        return $this->path;
    }
}
