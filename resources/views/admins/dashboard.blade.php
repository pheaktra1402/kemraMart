@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('admin_content')

<style>
    :root { --km-brown: #6b2d12; --km-gold: #e29a2d; --km-cream: #fff8ee; }
    .stat-card { border: 0; border-radius: 16px; background: var(--km-cream); }
    .stat-number { font-size: 34px; font-weight: 800; color: var(--km-brown); line-height: 1; }
    .stat-label { color: #7a6a5c; font-size: 14px; }
    .stat-card.alert-card { border-left: 5px solid #dc3545; }
    .panel { border: 0; border-radius: 16px; }
    .panel-title { color: var(--km-brown); font-weight: 700; }
</style>
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body">
                <div class="stat-number">{{ $totalCustomers }}</div>
                <div class="stat-label mt-2">Customers</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body">
                <div class="stat-number">{{ $totalProducts }}</div>
                <div class="stat-label mt-2">Products</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card stat-card shadow-sm h-100">
            <div class="card-body">
                <div class="stat-number">{{ $totalCategories }}</div>
                <div class="stat-label mt-2">Categories</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card stat-card alert-card shadow-sm h-100">
            <div class="card-body">
                <div class="stat-number">{{ $outOfStock }}</div>
                <div class="stat-label mt-2">Out of stock</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-7">
        <div class="card panel shadow-sm h-100">
            <div class="card-body">
                <h5 class="panel-title mb-3">Low Stock (10 or fewer)</h5>

                @if($lowStock->isEmpty())
                    <p class="text-muted mb-0">All products are well stocked.</p>
                @else
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Category</th>
                                    <th class="text-end">Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lowStock as $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->category->name ?? 'Uncategorized' }}</td>
                                        <td class="text-end">
                                            <span class="badge {{ $product->stock == 0 ? 'bg-danger' : 'bg-warning text-dark' }}">
                                                {{ $product->stock }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
    </div>

</div>

@endsection