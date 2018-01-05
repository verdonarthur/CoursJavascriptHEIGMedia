<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Item;

class ItemTableSeeder extends Seeder {

    public function run()
    {
        DB::table('items')->delete();
        $user1 = User::all()->get(0);
        // Une des multiples syntaxes possibles offertes par Laravel
        Item::create([
            'title' => 'truc1',
            'user_id' => $user1->id
        ]);
        Item::create([
            'title' => 'truc2',
            'user_id' => $user1->id
        ]);
        Item::create([
            'title' => 'truc3',
            'user_id' => $user1->id
        ]);
        // Exemple avec une autre syntaxe
        $user2 = User::all()->get(1);
        $user2->items()->saveMany([
            new Item(['title' => 'Machin1']),
            new Item(['title' => 'Machin2']),
            new Item(['title' => 'Machin3']),
            new Item(['title' => 'Machin4']),
        ]);
    }

}
