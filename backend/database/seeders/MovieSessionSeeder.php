<?php

namespace Database\Seeders;

use App\Models\MovieSession;
use Illuminate\Database\Seeder;

class MovieSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MovieSession::factory()->count(80)->create();
    }
}
