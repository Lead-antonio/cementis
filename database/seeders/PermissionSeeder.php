<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create([
            'name' => 'new.scoring',
            'guard_name' => 'web'
        ]);
          // Récupérer le rôle par son nom
        $role = Role::findByName('Guest');

        // Attribuer la permission au rôle
        $role->givePermissionTo('new.scoring');

        // Récupérer les permissions associées à ce rôle
        $permissions = $role->permissions;
        
    }

  

}
