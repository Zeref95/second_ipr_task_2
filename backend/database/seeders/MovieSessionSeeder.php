<?php

namespace Database\Seeders;

use App\Models\MovieSession;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MovieSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = MovieSession::factory()->count(3000)->make();
        $data = $data->unique(function ($item) {
            return $item['city_id'].$item['movie_id'].$item['date'].$item['time'];
        });

        DB::transaction(function () use ($data) {
            $data->each(function ($item) {
                $item->save();
            });
        });
    }
}
