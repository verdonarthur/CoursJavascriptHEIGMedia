<?php

use Illuminate\Database\Seeder;
use App\User;

class UserTableSeeder extends Seeder {

    public function run()
    {
        DB::table('users')->delete();
        User::create([
            'email' => 'moderator@bar.com',
            'password' => bcrypt('123456'),
        ]);
        User::create([
            'email' => 'contributor@bar.com',
            'password' => bcrypt('123456'),
        ]);
        User::create([
            'email' => 'admin@bar.com',
            'password' => bcrypt('123456'),
        ]);        
    }

}
