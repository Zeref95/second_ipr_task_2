<?php

namespace Database\Factories;

use App\Http\Controllers\MovieSessionController;
use App\Models\City;
use App\Models\Movie;
use App\Models\MovieSession;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class MovieSessionFactory extends Factory
{
    protected $model = MovieSession::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $countDays = 5;
        $timeList = [
            '09:30',
            '11:30',
            '12:20',
            '15:40',
            '19:20',
            '22:00'
        ];

        return [
            'city_id' => City::all()->random()->id,
            'movie_id' => Movie::all()->random()->id,
            'date' =>  Carbon::now()
                ->addDays(rand(0,$countDays)),
            'time' => $timeList[array_rand($timeList, 1)],
            'plates' => MovieSessionController::getEmptyPlacesJson(),
        ];
    }
}
