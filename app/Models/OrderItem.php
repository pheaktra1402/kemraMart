<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $guarded = [];

    public function order()
    {
        Product::withSum([
            'orderItems as total_sold' => function ($q) {
                $q->whereHas('order', fn($o) => $o->where('status', 'paid'));
            }
        ], 'qty')
            ->having('total_sold', '>', 0)
            ->orderByDesc('total_sold')
            ->limit(4)
            ->get();
        return $this->belongsTo(Order::class);
    }
}