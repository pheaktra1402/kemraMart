<?php

namespace App\Services;

use Carbon\Carbon;

class KhqrService
{
    public function generate(float $amount, string $currency, string $billNumber, int $expireMinutes = 10): array
    {
        $accountId = config('services.khqr.account_id');
        $merchantName = config('services.khqr.merchant_name');
        $city = config('services.khqr.merchant_city', 'Phnom Penh');

        $isUsd = strtoupper($currency) === 'USD';

        $now = Carbon::now();
        $expires = $now->copy()->addMinutes($expireMinutes);

        $payload = $this->tlv('00', '01');
        $payload .= $this->tlv('01', '12');
        $payload .= $this->tlv(
            '29',
            $this->tlv('00', $accountId) .
            $this->tlv('01', config('services.khqr.account_number')) .
            $this->tlv('02', config('services.khqr.bank_name'))
        );

        $payload .= $this->tlv(
            '40',
            $this->tlv('00', 'abaP2P') .
            $this->tlv('01', config('services.khqr.p2p_ref')) .
            $this->tlv('02', config('services.khqr.p2p_account_1')) .
            $this->tlv('03', config('services.khqr.p2p_account_2')) .
            $this->tlv('04', 'Dual')
        );
        $payload .= $this->tlv('52', '5999');
        $payload .= $this->tlv('53', $isUsd ? '840' : '116');
        $payload .= $this->tlv('54', $isUsd
            ? number_format($amount, 2, '.', '')
            : (string) (int) round($amount));
        $payload .= $this->tlv('58', 'KH');
        $payload .= $this->tlv('59', mb_substr($merchantName, 0, 25));
        $payload .= $this->tlv('60', mb_substr($city, 0, 15));
        $payload .= $this->tlv('62', $this->tlv('01', substr($billNumber, 0, 25)));
        $payload .= $this->tlv(
            '99',
            $this->tlv('00', (string) $now->getTimestampMs()) .
            $this->tlv('01', (string) $expires->getTimestampMs())
        );

        $payload .= '6304';
        $payload .= $this->crc16($payload);

        return [
            'qr' => $payload,
            'md5' => md5($payload),
            'expires_at' => $expires,
        ];
    }

    private function tlv(string $tag, string $value): string
    {
        return $tag . str_pad((string) strlen($value), 2, '0', STR_PAD_LEFT) . $value;
    }

    private function crc16(string $data): string
    {
        $crc = 0xFFFF;
        for ($i = 0, $len = strlen($data); $i < $len; $i++) {
            $crc ^= ord($data[$i]) << 8;
            for ($j = 0; $j < 8; $j++) {
                $crc = ($crc & 0x8000) ? (($crc << 1) ^ 0x1021) : ($crc << 1);
                $crc &= 0xFFFF;
            }
        }
        return strtoupper(str_pad(dechex($crc), 4, '0', STR_PAD_LEFT));
    }
}