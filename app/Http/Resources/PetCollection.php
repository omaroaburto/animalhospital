<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\Traits\CleanPaginationTrait;

class PetCollection extends ResourceCollection
{
    use CleanPaginationTrait;
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(function ($pet){
                return new PetResource($pet);
            }),
            'meta' => array_merge([
                'total_pets' => $this->collection->count(), // Tu contador de la página actual
            ], $this->getCleanMeta()),
        ];
    }
}
