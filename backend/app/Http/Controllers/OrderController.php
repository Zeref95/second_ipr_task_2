<?php

namespace App\Http\Controllers;

use App\Models\MovieSession;
use App\Models\Ticket;
use App\Rules\IsPlacesArray;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $bodyContent = json_decode($request->getContent(), true);
        if (!$bodyContent) {
            return errorResponse($request->getContent());
        }
        $validator = Validator::make($bodyContent, [
            'is_test' => 'nullable|boolean',
            'session_id' => ['required','numeric','exists:App\Models\MovieSession,id',function ($attribute, $value, $fail) {
                $movieSession = MovieSession::find($value);
                if (!$movieSession) {
                    return;
                }
                $movieSessionDate = $movieSession->date.' '. $movieSession->time;
                if (Carbon::now() > Carbon::parse($movieSessionDate)) {
                    $fail('The selected session is no longer available');
                    return;
                }
            }],
            'places' => ['required', 'array', new IsPlacesArray()],
        ]);
        if ($validator->fails()) {
            return errorResponse($validator->getMessageBag());
        }
        $validated = $validator->validated();

        $places = $validated['places'];
        $movieSession = MovieSession::find($validated['session_id']);
        $movieSessionPlaces = collect(json_decode($movieSession->places));
        $PlacesCollection = $movieSessionPlaces->mapWithKeys(function ($item, $key) {
            return [$item->place => $item->status];
        });

        foreach ($places as $place) {
            if (!isset($PlacesCollection[$place])) {
                return errorResponse(["places" => "One of the selected places dose not exist on this session"]);
            }
            if ($PlacesCollection[$place] != MovieSession::FREE) {
                return errorResponse(["places" => "One of the selected places is not free"]);
            }
            $movieSessionPlaces->firstWhere('place', $place)->status = MovieSession::TAKEN;
        }

        $movieSession->places = $movieSessionPlaces->toJson();

        $is_test = isset($validated['is_test']) && $validated['is_test'] == 1;
        if (!$is_test) {
            DB::beginTransaction();
            try {
                $movieSession->save();
                Ticket::create([
                    'movie_session_id' => $validated['session_id'],
                    'places' => json_encode($validated['places'])
                ]);
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
            }
        }

        $seats = '';
        foreach ($validated['places'] as $key=>$place) {
            $seats .= $key == 0 ? $place : ', '.$place;
        }

        return response()->json([
            'status' => 'OK',
            'message' => 'Order successfully created'.($is_test ? ' (test order)' : ''),
            'move_name' => $movieSession->movie->title,
            'date_time' => $movieSession->date.' '.substr($movieSession->time, 0,5),
            'seats' => $seats
            ], 200);
    }
}
