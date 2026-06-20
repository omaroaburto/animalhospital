<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PetResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'gender' => $this->gender,
            'birth_date' => $this->birth_date,
            'death_date' => $this->death_date,
            'species' => new SpeciesResource($this->whenLoaded('species')),
            'breed' => new BreedResource($this->whenLoaded('breed')),
            'client' => new ClientResource($this->whenLoaded('client')),
        ];
    }
}
