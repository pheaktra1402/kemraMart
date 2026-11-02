@extends('layouts.app')

@section('content')

@php
    // label => [route name, active pattern]
    $menu = [
        'Dashboard'  => ['admin.dashboard',        'admin.dashboard'],
        'Products'   => ['admin.products.index',   'admin.products.*'],
        'Categories' => ['admin.categories.index', 'admin.categories.*'],
        'Orders'     => ['admin.orders.index',     'admin.orders.*'],
        'Stock'      => ['admin.stock.index',      'admin.stock.*'],
        'Users'      => ['admin.user.index',       'admin.user.*'],
    ];
@endphp

<style>
    :root {
        --km-brown: #6b2d12;
        --km-gold: #e29a2d;
        --km-cream: #fff8ee;
    }
    .admin-sidebar { position: sticky; top: 80px; }
    .admin-sidebar .list-group-item {
        border: 0;
        border-left: 4px solid transparent;
        color: var(--km-brown);
        font-weight: 500;
        padding: 12px 16px;
        transition: background .15s, border-color .15s;
    }
    .admin-sidebar .list-group-item:hover {
        background: var(--km-cream);
        color: var(--km-brown);
    }
    .admin-sidebar .list-group-item.active {
        background: var(--km-cream);
        color: var(--km-brown);
        font-weight: 700;
        border-left-color: var(--km-gold);
    }
</style>

<div class="container-fluid my-4 px-lg-4">
    <div class="row g-4">

        {{-- SIDEBAR --}}
        <div class="col-lg-2">
            <nav class="list-group admin-sidebar shadow-sm rounded-3 overflow-hidden">
                @foreach ($menu as $label => [$routeName, $pattern])
                    <a href="{{ route($routeName) }}"
                       class="list-group-item list-group-item-action {{ request()->routeIs($pattern) ? 'active' : '' }}"
                       @if (request()->routeIs($pattern)) aria-current="page" @endif>
                        {{ $label }}
                    </a>
                @endforeach
            </nav>
        </div>


        <div class="col-lg-10">
          @yield('admin_content')
        </div>

    </div>
</div>

@endsection