<?php

namespace App\Http\Controllers;

use App\Http\Resources\SpeciesCollection;
use App\Http\Resources\SpeciesResource;
use App\Models\Species;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SpeciesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): SpeciesCollection | JsonResponse
    {
        $species = Species::all();
        return new SpeciesCollection($species);
    }
    /**
     * Display the specified resource.
     */
    public function show(Species $species): SpeciesResource | JsonResponse
    {
        $species->load('breeds');
        return new SpeciesResource($species);
    }
}
