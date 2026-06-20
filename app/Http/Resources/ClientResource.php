<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "first_name" => $this->first_name,
            "paternal_name" => $this->paternal_name,
            "maternal_name"=> $this->maternal_name, 
            "email" => $this->whenLoaded('user', fn() => $this->user->email),
            "phone" => $this->phone,
            "rut" => $this->rut,
            "street" => $this->street,
            "street_number" => $this->street_number,
            "apartment_number" => $this->apartment_number,
            "city" => $this->whenLoaded('city', fn() => $this->city->name),
            "region" => $this->whenLoaded('city', fn() => $this->city->region->name),
            //"pets" => PetResource::collection($this->whenLoaded('pets')),
        ];
    }
}
