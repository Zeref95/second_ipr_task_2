<?php

namespace App\Http\Controllers;

use App\Http\Resources\MovieSessionResource;
use App\Models\MovieSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MovieSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'movie_id' => 'required|numeric|exists:App\Models\Movie,id',
            'date' => 'required|date|date_format:Y-m-d',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => 'true',
                'message' => $validator->getMessageBag()
            ], 400);
        }
        $validated = $validator->validated();

        $movie_sessions = MovieSession::where('movie_id', $validated['movie_id'])
            ->whereDate('date', $validated['date'])
            ->get();

        return response()->json(MovieSessionResource::collection($movie_sessions), 200);
    }

    /**
     * Return JSON template for Movies Session.
     *
     * @return \Illuminate\Http\Response|string
     */
    public static function getEmptyPlacesJson()
    {
        $places = [];
        for ($i = 0; $i < 3; $i++) {
            for ($j = 0; $j < 7; $j++) {
                $places[] = [
                    'place' => ($i*7 + $j)+1,
                    'row' => $i+1,
                    'status' => MovieSession::FREE
                ];
            }
        }
        return json_encode($places);
    }
}
