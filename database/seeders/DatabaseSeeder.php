<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Users
        User::create([
            'name' => 'Manager Utama',
            'email' => 'manager@coosae.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
        ]);

        User::create([
            'name' => 'Budi Marketing',
            'email' => 'marketing@coosae.com',
            'password' => Hash::make('password'),
            'role' => 'marketing',
        ]);

        // Products
        $products = [
            'Program Pelatihan Pertanian',
            'Layanan Konsultasi Pertanian',
            'Produk Hortikultura'
        ];

        foreach ($products as $product) {
            Product::create(['nama' => $product]);
        }
    }
}
