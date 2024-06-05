<?php

namespace Database\Seeders;

use App\Models\Obat;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CartItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $obats = Obat::all();

        $users->each(function ($user) use ($obats) {
            $obats->random(rand(1, 5))->each(function ($obat) use ($user) {
                $user->cartItems()->attach($obat, [
                    'quantity' => rand(1, 5),
                ]);
            });
        });
    }
}
