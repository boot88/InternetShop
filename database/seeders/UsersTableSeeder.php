<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $users = [
            [
                'name' => 'Администратор',
                'email' => 'admin@shop.ru',
                'password' => Hash::make('admin123'),
                'phone' => '+7 (999) 123-45-67',
                'address' => 'Москва, ул. Тверская, д. 1',
                'is_admin' => true,
                'email_verified_at' => now()
            ],
            [
                'name' => 'Иван Петров',
                'email' => 'ivan@mail.ru',
                'password' => Hash::make('password123'),
                'phone' => '+7 (916) 345-67-89',
                'address' => 'Москва, ул. Ленина, д. 25, кв. 12',
                'is_admin' => false,
                'email_verified_at' => now()
            ],
            [
                'name' => 'Мария Сидорова',
                'email' => 'maria@mail.ru',
                'password' => Hash::make('password123'),
                'phone' => '+7 (925) 567-89-01',
                'address' => 'Санкт-Петербург, Невский пр., д. 100',
                'is_admin' => false,
                'email_verified_at' => now()
            ],
            [
                'name' => 'Алексей Козлов',
                'email' => 'alex@mail.ru',
                'password' => Hash::make('password123'),
                'phone' => '+7 (903) 789-01-23',
                'address' => 'Екатеринбург, ул. Мира, д. 15',
                'is_admin' => false,
                'email_verified_at' => now()
            ],
            [
                'name' => 'Екатерина Волкова',
                'email' => 'ekaterina@mail.ru',
                'password' => Hash::make('password123'),
                'phone' => '+7 (495) 234-56-78',
                'address' => 'Новосибирск, ул. Кирова, д. 8',
                'is_admin' => false,
                'email_verified_at' => now()
            ]
        ];

        foreach ($users as $user) {
            DB::table('users')->updateOrInsert(
                ['email' => $user['email']],
                array_merge($user, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}