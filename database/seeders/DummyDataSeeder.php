<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
  public function run(): void
{
    User::updateOrCreate(
        ['email' => 'admin@kemramart.com'],
        ['name' => 'Admin', 'password' => Hash::make('123456'), 'role' => 'admin']
    );

    $cat = Category::updateOrCreate(['name' => 'Krama']);

    Product::updateOrCreate(
        ['name' => 'Khmer Krama'],
        ['category_id' => $cat->id, 'price' => 5.00, 'stock' => 50]
    );
}
}
