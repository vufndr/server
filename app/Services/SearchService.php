<?php

namespace App\Services;

use App\Models\ImageFile;

class ImageSearchService
{
    public function search(string $query = '')
    {
        $imageFileRepo = app(ImageFile::class);

        $builder = ImageFile::search($query)->with(['facets' => ['*']]);
        
        $results = $builder->raw();

        if (count($results['hits']) === 0) {
            return  $imageFileRepo->newCollection();
        }

        $objectIds = collect($results['hits'])->pluck('objectID')->values()->all();

        $objectIdPositions = array_flip($objectIds);

        $models = $imageFileRepo->getScoutModelsByIds(
            $builder, $objectIds
        )->filter(function ($imageFileRepo) use ($objectIds) {
            return in_array($imageFileRepo->getScoutKey(), $objectIds);
        })->sortBy(function ($imageFileRepo) use ($objectIdPositions) {
            return $objectIdPositions[$imageFileRepo->getScoutKey()];
        })->values();

        return [
            'results' => $models,
            'facets' => $results['hits'],
        ];
    }

    private function getPreviewUrl(ImageFile $image)
    {
    }
}
