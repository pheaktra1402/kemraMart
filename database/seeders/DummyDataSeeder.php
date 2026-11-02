<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@kemramart.com'],
            ['name' => 'Admin', 'password' => Hash::make('123456'), 'role' => 'admin']
        );

        $categoryIds = [];
        foreach (['Drinks', 'Fruits', 'Vegetables', 'Snacks'] as $name) {
            $categoryIds[$name] = Category::updateOrCreate(['name' => $name])->id;
        }

        $products = [
            ['category' => 'Drinks', 'name' => 'Cambodia Water', 'description' => 'drinking water.', 'price' => 2.00, 'stock' => 50, 'image' => 'water.jpg'],
            ['category' => 'Drinks', 'name' => 'Sting', 'description' => 'Energy drink.', 'price' => 0.01, 'stock' => 100, 'image' => 'sting.jpg'],
            ['category' => 'Fruits', 'name' => 'Grape', 'description' => 'Fresh sweet grapes.', 'price' => 2.50, 'stock' => 40, 'image' => 'grap.jpg'],
            ['category' => 'Fruits', 'name' => 'Banana', 'description' => 'Fresh local bananas.', 'price' => 0.01, 'stock' => 80, 'image' => 'banana.jpg'],
            ['category' => 'Fruits', 'name' => 'Avocado', 'description' => 'Creamy ripe avocado.', 'price' => 3.00, 'stock' => 30, 'image' => 'avocado.jpg'],
            ['category' => 'Vegetables', 'name' => 'Cucumber', 'description' => 'Crisp fresh cucumber.', 'price' => 0.01, 'stock' => 100, 'image' => 'cucumber.jpg'],
            ['category' => 'Snacks', 'name' => 'Cookie', 'description' => 'Sweet crunchy cookie.', 'price' => 3.00, 'stock' => 60, 'image' => 'cookie.jpg'],
            ['category' => 'Snacks', 'name' => 'Bread', 'description' => 'Sweet Bread.', 'price' => 3.00, 'stock' => 60, 'image' => 'bread.jpg'],
           
        ];

        foreach ($products as $item) {
            $data = [
                'category_id' => $categoryIds[$item['category']],
                'description' => $item['description'],
                'price' => $item['price'],
                'stock' => $item['stock'],
            ];

            $image = $this->storeImage($item['image']);
            if ($image) {
                $data['image'] = $image;
            }

            Product::updateOrCreate(['name' => $item['name']], $data);
        }
    }

    private function storeImage(string $file): ?string
    {
        $source = database_path('seed-images/' . $file);

        if (! File::exists($source)) {
            return null;
        }

        $path = 'products/' . $file;
        Storage::disk('public')->put($path, File::get($source));

        return $path;
    }
}