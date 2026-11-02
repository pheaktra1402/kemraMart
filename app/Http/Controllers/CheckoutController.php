<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Services\KhqrService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CheckoutController extends Controller
{
    public function checkout(KhqrService $khqr)
    {
        $cart = session('cart', []);
        if (empty($cart)) {
            return back()->with('error', 'Your cart is empty.');
        }
        $products = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');
        $validCart = array_filter($cart, fn($item, $id) => isset($products[$id]), ARRAY_FILTER_USE_BOTH);

        if (empty($validCart)) {
            return back()->with('error', 'Products in your cart are no longer available.');
        }

        $order = DB::transaction(function () use ($validCart, $products, $khqr) {
            $order = Order::create([
                'user_id' => auth()->id(),
                'total' => 0,
            ]);

            $total = 0;
            foreach ($validCart as $id => $item) {
                $product = $products[$id];

                $qty = max(1, (int) $item['quantity']);
                $total += $product->price * $qty;

                $order->items()->create([
                    'product_id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'qty' => $qty,
                ]);
            }

            $total = round($total, 2);

            $result = $khqr->generate($total, 'USD', 'INV' . $order->id);

            $order->update([
                'total' => $total,
                'khqr_string' => $result['qr'],
                'khqr_md5' => $result['md5'],
                'khqr_expires_at' => $result['expires_at'],
            ]);

            return $order;
        });

        return redirect()->route('orders.pay', $order);
    }

    public function pay(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        if ($order->status === 'paid') {
            return redirect()->route('orders.show', $order);
        }

        return view('checkout.qr', [
            'order' => $order,
            'qrSvg' => QrCode::size(300)->margin(1)->generate($order->khqr_string),
            'expiresAt' => $order->khqr_expires_at,
        ]);
    }

    public function checkPayment(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        if ($order->status === 'paid') {
            return response()->json(['paid' => true]);
        }

        // Call Bakong at most once every 10 seconds per order
        if (!Cache::add('khqr-check-' . $order->id, 1, 10)) {
            return response()->json(['paid' => false, 'throttled' => true]);
        }

        try {
            $res = Http::withToken(config('services.khqr.api_token'))
                ->acceptJson()
                ->timeout(15)
                ->post(
                    config('services.khqr.api_url', 'https://api-bakong.nbc.gov.kh') . '/v1/check_transaction_by_md5',
                    ['md5' => $order->khqr_md5]
                );
        } catch (\Throwable $e) {
            Log::warning('Bakong unreachable', ['order' => $order->id, 'message' => $e->getMessage()]);
            return response()->json(['paid' => false, 'error' => 'api_unreachable']);
        }

        if ($res->status() === 401) {
            Log::error('Bakong token expired or invalid. Renew it.');
            return response()->json(['paid' => false, 'error' => 'token_expired']);
        }

        if ($res->json('errorCode') === 17) {
            return response()->json(['paid' => false, 'error' => 'rate_limited']);
        }

        $data = $res->json('data');

        if ($res->ok() && $res->json('responseCode') === 0 && $data) {
            $okAccount = ($data['toAccountId'] ?? null) === config('services.khqr.account_id');
            $okCurrency = strtoupper($data['currency'] ?? '') === strtoupper($order->currency ?? 'USD');
            $okAmount = abs((float) ($data['amount'] ?? 0) - (float) $order->total) < 0.005;

            if ($okAccount && $okCurrency && $okAmount) {
                DB::transaction(function () use ($order) {
                    // Lock the order so overlapping checks can't reduce stock twice
                    $fresh = Order::with('items')->whereKey($order->id)->lockForUpdate()->first();

                    if ($fresh->status === 'paid') {
                        return;
                    }

                    foreach ($fresh->items as $item) {
                        Product::where('id', $item->product_id)->decrement('stock', (int) $item->qty);
                        Product::where('id', $item->product_id)->where('stock', '<', 0)->update(['stock' => 0]);
                    }

                    $fresh->update(['status' => 'paid', 'paid_at' => now()]);
                });

                session()->forget('cart');
                return response()->json(['paid' => true]);
            }

            Log::warning('Bakong payment mismatch', [
                'order' => $order->id,
                'account' => $okAccount,
                'currency' => $okCurrency,
                'amount' => $okAmount,
            ]);

            return response()->json(['paid' => false, 'error' => 'mismatch']);
        }

        return response()->json(['paid' => false]);
    }

    public function show(Order $order)
    {
        abort_unless($order->user_id === auth()->id(), 403);

        return view('orders.show', ['order' => $order->load('items')]);
    }
}