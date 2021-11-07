<?php

namespace App\Http\Controllers;

use App\Models\MovieSession;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'is_test' => 'nullable|boolean',
            'session_id' => ['required','numeric','exists:App\Models\MovieSession,id',function ($attribute, $value, $fail) {
                $movieSession = MovieSession::find($value);
                $movieSessionDate = $movieSession->date.' '. $movieSession->time;
                if (Carbon::now() > Carbon::parse($movieSessionDate)) {
                    $fail('The selected session is no longer available');
                    return;
                }
            }],
            'places' => ['required', function ($attribute, $value, $fail) {
                $places = json_decode($value);
                if (!$places || !is_array($places)) {
                    $fail('Not valid data');
                    return;
                }
                if (count($places) > 6 || count($places) <= 0) {
                    $fail('You can order 1 - 6 places for one time');
                    return;
                }
                foreach ($places as $key=>$place) {
                    if (!is_int($place) || $place < 0) {
                        $fail('Not valid data');
                        return;
                    }
                }
                if (count($places) != count(array_unique($places))) {
                    $fail('Duplicate values are specified in the order');
                    return;
                }
            },],
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => 'true',
                'message' => $validator->getMessageBag()
            ], 400);
        }
        $validated = $validator->validated();

        $places = json_decode($validated['places']);
        $movieSession = MovieSession::find($validated['session_id']);
        $movieSessionPlaces = collect(json_decode($movieSession->places));
        $PlacesCollection = $movieSessionPlaces->mapWithKeys(function ($item, $key) {
            return [$item->place => $item->status];
        });

        foreach ($places as $place) {
            if (!isset($PlacesCollection[$place])) {
                return response()->json(
                    [
                        "error" => "true",
                        "message" => ["places" => "One of the selected places dose not exist on this session"]
                    ], 400);
            }
            if ($PlacesCollection[$place] != MovieSession::FREE) {
                return response()->json(
                    [
                        "error" => "true",
                        "message" => ["places" => "One of the selected places is not free"]
                    ], 400);
            }
            $movieSessionPlaces->firstWhere('place', $place)->status = MovieSession::TAKEN;
        }

        $movieSession->places = $movieSessionPlaces->toJson();

        $is_test = isset($validated['is_test']) && $validated['is_test'] == 1;

        if (!$is_test) {
            $movieSession->save();
        }

        return response()->json([
            'status' => 'OK',
            'message' => 'Order successfully created'.($is_test ? ' (test order)' : ''),
        ], 200);
    }
}
