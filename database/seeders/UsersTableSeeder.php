<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 15; $i++) {
            $user = User::create([
                'name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password123'),
                'phone' => $faker->phoneNumber,
            ]);

            if ($i < 5) {
                $user->assignRole('proveedor');
            } else {
                $user->assignRole('cliente');
            }
        }
    }
}
