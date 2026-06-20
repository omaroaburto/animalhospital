<?php

namespace App\Http\Controllers;

use App\Http\Resources\CityCollection;
use App\Http\Resources\CityResource;
use App\Models\City; 
use Illuminate\Http\JsonResponse;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): CityCollection | JsonResponse 
    {
        $cities = City::all();
        return new CityCollection($cities);
    } 

    /**
     * Display the specified resource.
     */
    public function show(City $city)
    {
        $city->load('region');
        return new CityResource($city);
    }
 
}
