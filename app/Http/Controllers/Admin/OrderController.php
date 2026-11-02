<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
class OrderController extends Controller
{

public function index(Request $request)
{
    $orders = Order::with(['user', 'items'])
        ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
        ->when($request->filled('search'), function ($q) use ($request) {
            $term = $request->search;
            $q->where(function ($q) use ($term) {
                $q->where('id', $term)
                  ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$term}%"));
            });
        })
        ->latest()
        ->paginate(20)
        ->withQueryString();

    return view('admins.orders.index', compact('orders'));
}
}
