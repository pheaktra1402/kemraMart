<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::orderBy('name')->get();

        $products = Product::query()
            // filter by category
            ->when($request->filled('category'), function ($q) use ($request) {
                $q->where('category_id', $request->category);
            })
            // search by name or description
            ->when($request->filled('search'), function ($q) use ($request) {
                $term = $request->search;
                $q->where(function ($sub) use ($term) {
                    $sub->where('name', 'like', "%{$term}%")
                        ->orWhere('description', 'like', "%{$term}%");
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        // Popular products = most sold (sum of quantity in order_items).
        // Only shown on the normal home page, not while searching/filtering.
        $popular = null;

        if (!$request->filled('search') && !$request->filled('category')) {
            $popular = Product::query()
                ->with('category')
                ->withSum('orderItems as total_sold', 'qty')
                ->whereHas('orderItems')
                ->orderByDesc('total_sold')
                ->limit(4)
                ->get();
        }

        return view('welcome', compact('categories', 'products', 'popular'));
    }
}