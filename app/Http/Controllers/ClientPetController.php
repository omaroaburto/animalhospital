<?php

namespace App\Http\Controllers;

use App\Http\Resources\PetCollection;
use App\Models\Client;

class ClientPetController extends Controller
{
    public function index(Client $client): PetCollection
    {
        $pets =  $client->pets()->with(['breed.species'])->paginate(5);
        return new PetCollection($pets);
    }
}
