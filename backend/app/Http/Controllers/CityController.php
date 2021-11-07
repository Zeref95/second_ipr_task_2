<?php

namespace App\Http\Controllers;

use App\Http\Resources\CityResource;
use App\Models\City;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $city = City::all();
        if ($city) {
            return response()->json(CityResource::collection($city), 200);
        }

    }
}
