<?php

namespace App\Http\Controllers;

use App\Http\Resources\MovieResource;
use App\Models\City;
use App\Models\Movie;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MovieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'city_id' => 'required_without:city_name|numeric|exists:App\Models\City,id',
            'city_name' => 'required_without:city_id|string|exists:App\Models\City,name',
            'date' => 'nullable|date|date_format:Y-m-d',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => 'true',
                'message' => $validator->getMessageBag()
            ], 400);
        }
        $validated = $validator->validated();

        $date_start = $validated['date'] ?? Carbon::today()->toDateString();
        $date_end = $validated['date'] ?? Carbon::tomorrow()->toDateString();

        $city_id = $validated['city_id'] ?? City::where('name', $validated['city_name'])->first()->id;
        $movies = Movie::whereHas('movie_session', function ($query) use ($date_start, $date_end, $city_id) {
                $query->whereBetween('date', [$date_start, $date_end]);
                $query->where('city_id', $city_id);
            })
            ->get();

        return response()->json(MovieResource::collection($movies), 200);
    }
}
