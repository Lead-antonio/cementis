<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChauffeurUpdateTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            ['name' => 'Changement RFID pour la même personne', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Changement propriétaire', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Changement de transporteur', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Suppression', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Creation', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('chauffeur_update_type')->insertOrIgnore($types);
    }
}
