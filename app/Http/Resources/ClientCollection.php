<?php

namespace App\Http\Resources;

use App\Http\Resources\Traits\CleanPaginationTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ClientCollection extends ResourceCollection
{
    use CleanPaginationTrait;
    public function toArray(Request $request): array
    {

        return [
           'data' => $this->collection->map(function ($client){
                return new ClientResource($client);
           }),
           'meta' => array_merge([
                'total_clients' => $this->collection->count(), // Tu contador de la página actual
            ], $this->getCleanMeta()),
        ];
    }
}
