<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(PenaliteTableSeeder::class);
        $this->call(ChauffeurTableSeeder::class);
        $this->call(TransporteurSeeder::class);
        $this->call(VehiculeTableSeeder::class);
        // $this->call(ChauffeursTableSeeder::class);
        // $this->call(EventTableSeeder::class);
        // $this->call(PenaliteChauffeurTableSeeder::class);
        // $this->call(RotationTableSeeder::class);
    }
}
