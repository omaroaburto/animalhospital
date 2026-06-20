<?php

namespace App\Http\Controllers;

use App\Http\Resources\RegionCollection;
use App\Http\Resources\RegionResource;
use App\Models\Region;
use Illuminate\Http\JsonResponse; 
use Illuminate\Http\Request;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): RegionCollection
    {
        $regions = Region::all();
        return new RegionCollection($regions);
    }
 

    /**
     * Display the specified resource.
     */
    public function show(Region $region): RegionResource|JsonResponse 
    {
        $region->load('cities');
        return new RegionResource($region);
    }

}
