@extends('layouts.app')

@section('content')

<style>
    :root {
        --km-brown: #6b2d12;
        --km-gold: #e29a2d;
        --km-cream: #fff8ee;
    }
    .admin-sidebar .list-group-item {
        border: 0;
        color: var(--km-brown);
        font-weight: 500;
        padding: 12px 16px;
    }
    .admin-sidebar .list-group-item:hover {
        background: var(--km-cream);
        color: var(--km-brown);
    }
    .admin-sidebar .list-group-item.active {
        background: var(--km-brown);
        color: #fff;
    }
    .admin-sidebar .list-group-item.disabled {
        color: #aaa;
    }
</style>

<div class="container-fluid my-4 px-lg-4">
    <div class="row g-4">

        {{-- SIDEBAR --}}
        <div class="col-lg-2">
            <div class="list-group admin-sidebar shadow-sm rounded-3 overflow-hidden">
                <a href="{{ route('admin.dashboard') }}"
                   class="list-group-item list-group-item-action {{ request()->routeIs('admins.dashboard') ? 'active' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('admin.products.index') }}"
                   class="list-group-item list-group-item-action {{ request()->routeIs('admins.products.*') ? 'active' : '' }}">
                    Products
                </a>
                <a href="{{ route('admin.categories.index') }}"
                   class="list-group-item list-group-item-action {{ request()->routeIs('admins.categories.*') ? 'active' : '' }}">
                    Categories
                </a>
                
                <a href="{{ route('admin.orders.index') }}" 
                    class="list-group-item list-group-item-action {{ request()->routeIs('admins.orders.*') ? 'active' : ''}} ">
                    Orders
                </a>
                <a href="{{ route('admin.stock.index') }}"
                class="list-group-item list-group-item-action {{ request()->routeIs('admin.stock.*') ? 'active' : '' }}">
                 Stock
                </a>
                  <a href="{{ route('admin.users') }}"
                   class="list-group-item list-group-item-action {{ request()->routeIs('admins.users') ? 'active' : '' }}">
                    Users
                </a>
            </div>
        </div>

        {{-- PAGE CONTENT --}}
        <div class="col-lg-10">
            @yield('admin_content')
        </div>

    </div>
</div>

@endsection