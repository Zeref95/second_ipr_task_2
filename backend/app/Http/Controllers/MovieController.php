<?php

namespace App\Http\Controllers;

use App\Http\Resources\MovieResource;
use App\Models\City;
use App\Models\Movie;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MovieController extends Controller
{
    private function getMoviesByDateFromDB(int $city_id, string $date)
    {
        return Movie::whereHas('movie_session', function ($query) use ( $date, $city_id) {
            $query->whereDate('date',  $date);
            $query->where('city_id', $city_id);
        })->get();
    }

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
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => 'true',
                'message' => $validator->getMessageBag()
            ], 400);
        }
        $validated = $validator->validated();

        $city_id = $validated['city_id'] ?? City::where('name', $validated['city_name'])->first()->id;
        $moviesToday = self::getMoviesByDateFromDB($city_id, Carbon::today()->toDateString());
        $moviesTomorrow = self::getMoviesByDateFromDB($city_id, Carbon::tomorrow()->toDateString());

        Log::debug(Carbon::today()->toDateString());
        Log::debug(Carbon::tomorrow()->toDateString());
        return response()->json([
            'today' => MovieResource::collection($moviesToday),
            'tomorrow' => MovieResource::collection($moviesTomorrow)
        ] , 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMoviesByDate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'city_id' => 'required_without:city_name|numeric|exists:App\Models\City,id',
            'city_name' => 'required_without:city_id|string|exists:App\Models\City,name',
            'date' => 'date|date_format:Y-m-d',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => 'true',
                'message' => $validator->getMessageBag()
            ], 400);
        }
        $validated = $validator->validated();

        $city_id = $validated['city_id'] ?? City::where('name', $validated['city_name'])->first()->id;
        $movies = self::getMoviesByDateFromDB($city_id, $validated['date']);

        return response()->json(MovieResource::collection($movies), 200);
    }
}
