<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CityCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(function ($city){
                return new CityResource($city);
            }),
            'meta' => [
                'total_cities' => $this->collection->count(),
                'version_api' => '1.0.0'
            ]
        ];
    }
}
