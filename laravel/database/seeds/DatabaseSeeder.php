<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();
                // Auth et ACL
		$this->call('UserTableSeeder');
                $this->call('RoleTableSeeder');
                $this->call('PermissionTableSeeder');
                // Items
                $this->call('ItemTableSeeder');
	}

}
