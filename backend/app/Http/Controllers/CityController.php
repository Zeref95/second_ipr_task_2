<?php

namespace App\Http\Controllers;

use App\Http\Resources\CityResource;
use App\Models\City;
use Illuminate\Http\JsonResponse;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $city = City::all();
        if ($city) {
            return response()->json(CityResource::collection($city), 200);
        }

    }
}
