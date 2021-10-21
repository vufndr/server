<?php

namespace App\Clients\Dropbox;

use Spatie\Dropbox\Client;

class DropboxClient extends Client
{
    public function listFolder(string $path = '', bool $recursive = false, bool $include_deleted = false): array
    {
        $parameters = [
            'path' => $this->normalizePath($path),
            'recursive' => $recursive,
            'include_deleted' => $include_deleted,
        ];

        return $this->rpcEndpointRequest('files/list_folder', $parameters);
    }
}
