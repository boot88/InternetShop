<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CartsTableSeeder extends Seeder
{
    public function run()
    {
        $carts = [
            [
                'user_id' => 2,
                'session_id' => null,
                'created_at' => now()->subDays(2)
            ],
            [
                'user_id' => 3,
                'session_id' => null,
                'created_at' => now()->subDays(1)
            ],
            [
                'user_id' => null,
                'session_id' => 'session_guest_001',
                'created_at' => now()->subHours(3)
            ]
        ];

        foreach ($carts as $cart) {
            DB::table('carts')->insert(array_merge($cart, [
                'updated_at' => now(),
            ]));
        }
    }
}