<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SpeciesCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(function ($specie){
                return new SpeciesResource($specie);
            }),
            'meta' => [
                'total_species' => $this->collection->count(),
                'version' => '1.0.0'
            ]
        ];
    }
}
