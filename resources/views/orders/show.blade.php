@extends('layouts.app')

@section('content')
@php
    $isPaid = $order->status === 'paid';
    $itemCount = $order->items->sum('qty');
@endphp

<style>
    .order-hero { border-radius: 1rem; }
    .order-icon {
        width: 64px; height: 64px; border-radius: 50%;
        display: inline-flex; align-items: center; justify-content: center;
    }
    .order-card { border: 0; border-radius: 1rem; }
    .order-card .table > :not(caption) > * > * { padding: .9rem 1.25rem; }
    .meta-label { font-size: .75rem; text-transform: uppercase; letter-spacing: .05em; color: #6c757d; }
    @media print {
        .no-print { display: none !important; }
        .order-card { box-shadow: none !important; }
    }
</style>

<div class="container py-5" style="max-width: 760px;">
    <div class="text-center mb-4">
        @if($isPaid)
            <div class="order-icon bg-success-subtle text-success mb-3">
                <svg width="32" height="32" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M13.485 3.929a.75.75 0 0 1 0 1.06l-6.5 6.5a.75.75 0 0 1-1.06 0l-3-3a.75.75 0 1 1 1.06-1.06L6.455 9.9l5.97-5.97a.75.75 0 0 1 1.06 0z"/>
                </svg>
            </div>
            <h2 class="fw-bold mb-1">Payment successful</h2>
            <p class="text-muted mb-0">Thank you for support us.</p>
        @else
            <div class="order-icon bg-warning-subtle text-warning mb-3">
                <svg width="32" height="32" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 3.5a.5.5 0 0 1 .5.5v4.21l2.85 1.7a.5.5 0 1 1-.5.86l-3.1-1.85A.5.5 0 0 1 7.5 8.5V4a.5.5 0 0 1 .5-.5z"/>
                    <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/>
                </svg>
            </div>
            <h2 class="fw-bold mb-1">Awaiting payment</h2>
            <p class="text-muted mb-3">This order has not been paid yet.</p>
            <a href="{{ route('orders.pay', $order) }}" class="btn btn-primary no-print">Pay now</a>
        @endif
    </div>

    <div class="card order-card shadow-sm">
        <div class="card-body border-bottom">
            <div class="row g-3">
                <div class="col-6 col-md-3">
                    <div class="meta-label">Your Order</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="meta-label">Date</div>
                    <div class="fw-semibold">{{ $order->created_at->format('d M Y, H:i') }}</div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="meta-label">Status</div>
                    <span class="badge rounded-pill {{ $isPaid ? 'text-bg-success' : 'text-bg-warning' }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                <div class="col-6 col-md-3">
                    <div class="meta-label">{{ $isPaid ? 'Paid on' : 'Items' }}</div>
                    <div class="fw-semibold">
                        @if($isPaid && $order->paid_at)
                            {{ $order->paid_at->format('d M Y, H:i') }}
                        @else
                            {{ $itemCount }}
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Items --}}
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Item</th>
                        <th class="text-end">Price</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td class="fw-medium">{{ $item->name }}</td>
                            <td class="text-end">${{ number_format($item->price, 2) }}</td>
                            <td class="text-center">{{ $item->qty }}</td>
                            <td class="text-end">${{ number_format($item->price * $item->qty, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Total --}}
        <div class="card-body bg-light d-flex justify-content-between align-items-center"
             style="border-radius: 0 0 1rem 1rem;">
            <span class="text-muted">{{ $itemCount }} {{ \Illuminate\Support\Str::plural('item', $itemCount) }}</span>
            <div class="text-end">
                <div class="meta-label">Total</div>
                <div class="fs-4 fw-bold">${{ number_format($order->total, 2) }}</div>
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="d-flex flex-wrap justify-content-center gap-2 mt-4 no-print">
        <a href="{{ route('shop') }}" class="btn btn-primary px-4">Continue shopping</a>
        <button type="button" class="btn btn-outline-secondary px-4" onclick="window.print()">Print receipt</button>
    </div>
</div>
@endsection