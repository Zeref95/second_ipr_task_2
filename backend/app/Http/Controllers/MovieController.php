<?php

namespace App\Http\Controllers;

use App\Http\Resources\MovieResource;
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
            'city_id' => 'required|numeric|exists:App\Models\City,id',
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
        $movies = Movie::whereHas('movie_session', function ($query) use ($date_start, $date_end) {
                $query->whereBetween('date', [$date_start, $date_end]);
            })
            ->get();

        return response()->json(MovieResource::collection($movies), 200);
    }
}
