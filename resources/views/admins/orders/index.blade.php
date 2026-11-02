@extends('layouts.admin')

@section('title', 'Orders')

@section('admin_content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">Orders</h3>
    </div>

    <form method="GET" class="row g-2 mb-3">
        <div class="col-md-5">
            <input type="text" name="search" class="form-control"
                   placeholder="Search by order  or customer name" value="{{ request('search') }}">
        </div>
        <div class="col-md-4">
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="">All statuses</option>
                <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                <option value="paid" @selected(request('status') === 'paid')>Paid</option>
            </select>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-dark w-100">Filter</button>
        </div>
    </form>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Paid at</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->user->name ?? 'Guest' }}</td>
                            <td>
                                @foreach($order->items as $item)
                                    <div class="small">{{ $item->name }} x {{ $item->qty }}</div>
                                @endforeach
                            </td>
                            <td>${{ number_format($order->total, 2) }}</td>
                            <td>
                                <span class="badge {{ $order->status === 'paid' ? 'bg-success' : 'bg-warning text-dark' }}">
                                    {{ ucfirst($order->status ?? 'pending') }}
                                </span>
                            </td>
                            <td>{{ $order->paid_at?->format('Y-m-d H:i') ?? '-' }}</td>
                            <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">No orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $orders->links() }}</div>
@endsection