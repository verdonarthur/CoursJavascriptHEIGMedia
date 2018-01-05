<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\User;

class RoleTableSeeder extends Seeder {

    public function run()
    {
        DB::table('roles')->delete();

        $admin = Role::create([
            'label' => 'admin',
        ]);
        $moderator = Role::create([
            'label' => 'moderator',
        ]);
        $contributor = Role::create([
            'label' => 'contributor',
        ]);

        $moderatorUser = User::where('email', '=', 'moderator@bar.com')->firstOrFail();
        // Ou mieux avec la méthode whereFiledname :
        $contributorUser = User::whereEmail('contributor@bar.com')->firstOrFail();
	$adminUser = User::whereEmail('admin@bar.com')->firstOrFail();

        $moderatorUser->roles()->save($moderator);
        $contributorUser->roles()->save($contributor);
        $adminUser->roles()->saveMany([$admin, $moderator, $contributor]);
    }

}
