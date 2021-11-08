<?php

namespace Database\Factories;

use App\Http\Controllers\MovieSessionController;
use App\Models\City;
use App\Models\Movie;
use App\Models\MovieSession;
use Illuminate\Database\Eloquent\Factories\Factory;

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
        $timeList = [
            '09:30',
            '11:30',
            '12:20',
            '15:40',
            '19:20',
            '22:00'
        ];
        $movie = Movie::all()->random();
        return [
            'city_id' => City::all()->random()->id,
            'movie_id' => $movie->id,
            'date' =>  $this->faker->dateTimeBetween($movie->rental_start, $movie->rental_end)->format('Y-m-d'),
            'time' => $timeList[array_rand($timeList, 1)],
            'places' => MovieSessionController::getEmptyPlacesJson(),
        ];
    }
}
