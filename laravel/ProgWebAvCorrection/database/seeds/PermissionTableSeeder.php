<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\Permission;

class PermissionTableSeeder extends Seeder {

    public function run()
    {
        DB::table('permissions')->delete();

        // Persmissions CRUD/REST (basés sur les noms par défaut des routes REST de Laravel)
        $itemList = Permission::create([
            'label' => 'item.index'
        ]);
        $itemCreate = Permission::create([
            'label' => 'item.store',
        ]);
        $itemRead = Permission::create([
            'label' => 'item.show',
        ]);
        $itemUpdate = Permission::create([
            'label' => 'item.update',
        ]);
        $itemDelete = Permission::create([
            'label' => 'item.destroy',
        ]);

        $moderator = Role::whereLabel('moderator')->firstOrFail();
        $contributor = Role::whereLabel('contributor')->firstOrFail();

        $moderator->permissions()->saveMany([$itemList, $itemCreate, $itemRead, $itemUpdate, $itemDelete]);
        $contributor->permissions()->saveMany([$itemList, $itemCreate, $itemRead]);

    }

}
