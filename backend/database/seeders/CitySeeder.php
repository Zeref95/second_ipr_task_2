<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cityList = ['Taganrog', 'Rostov', 'Moscow'];
        foreach ($cityList as $cityName) {
            City::firstOrCreate([
                'name' => $cityName
            ]);
        }
    }
}
