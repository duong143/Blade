<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AirportSeeder::class,
            AirlineSeeder::class,
            FlightSeeder::class,
            ComboBulkSeeder::class,
        ]);
    }
}