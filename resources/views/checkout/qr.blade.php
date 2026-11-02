<div class="pay-card">
    <p class="pay-order">Order #{{ $order->id }}</p>
    <h1 class="pay-total">${{ number_format($order->total, 2) }}</h1>

    <div class="pay-qr">{!! $qrSvg !!}</div>

    <p class="pay-hint">Scan with ABA Mobile or any Bakong banking app</p>
    <p id="status" class="pay-status">Waiting for payment...</p>
    <p class="pay-timer">Expires in <strong id="timer">--:--</strong></p>
</div>

<style>
    .pay-card {
        max-width: 340px; margin: 40px auto; padding: 24px; text-align: center;
        font-family: system-ui, -apple-system, "Segoe UI", sans-serif;
        border: 1px solid #e5e7eb; border-radius: 16px;
        box-shadow: 0 8px 24px rgba(0,0,0,.06);
    }
    .pay-order  { margin: 0; font-size: 13px; color: #6b7280; }
    .pay-total  { margin: 4px 0 16px; font-size: 32px; }
    .pay-qr     { max-width: 260px; margin: 0 auto; background: #fff; }
    .pay-qr svg { width: 100%; height: auto; display: block; }
    .pay-hint   { margin: 14px 0 10px; font-size: 14px; color: #4b5563; }
    .pay-timer  { margin: 12px 0 0; font-size: 13px; color: #6b7280; }
    .pay-status {
        display: inline-block; margin: 0; padding: 6px 14px;
        border-radius: 999px; font-size: 14px;
        background: #fef3c7; color: #92400e;
    }
    .pay-status.paid    { background: #d1fae5; color: #065f46; }
    .pay-status.expired { background: #fee2e2; color: #991b1b; }
</style>

<script>
const checkUrl   = @json(route('orders.checkPayment', $order));
const successUrl = @json(route('orders.show', $order));
const expiresAt  = new Date(@json($expiresAt?->toIso8601String()));
const INTERVAL   = 15000;

const statusEl = document.getElementById('status');
const timerEl  = document.getElementById('timer');

function setStatus(text, cls = '') {
    statusEl.textContent = text;
    statusEl.className = 'pay-status ' + cls;
}

function stopAll() {
    clearInterval(timer);
    clearInterval(tickTimer);
}

function tick() {
    const left = Math.max(0, Math.floor((expiresAt.getTime() - Date.now()) / 1000));
    const m = String(Math.floor(left / 60)).padStart(2, '0');
    const s = String(left % 60).padStart(2, '0');
    timerEl.textContent = `${m}:${s}`;
}

async function check() {
    if (document.hidden) return;

    if (Date.now() > expiresAt.getTime()) {
        stopAll();
        setStatus('QR code expired. Please refresh.', 'expired');
        return;
    }

    try {
        const res = await fetch(checkUrl, {
            headers: { 'Accept': 'application/json' },
            credentials: 'same-origin',
        });
        const data = await res.json();

        if (data.paid) {
            stopAll();
            setStatus('Payment received ✓', 'paid');
            setTimeout(() => window.location.href = successUrl, 1500);
        } else if (data.error === 'rate_limited') {
            stopAll();
            setStatus('Check limit reached. Please contact the shop.', 'expired');
        } else if (data.error === 'mismatch') {
            stopAll();
            setStatus('Payment amount or account does not match. Please contact the shop.', 'expired');
        } else if (data.error === 'token_expired') {
            stopAll();
            setStatus('Payment system error. Please contact the shop.', 'expired');
        }
    } catch (e) {
        console.error('check failed', e);
    }
}

tick();
const tickTimer = setInterval(tick, 1000);
const timer = setInterval(check, INTERVAL);
</script>