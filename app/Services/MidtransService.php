<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class MidtransService
{
    protected string $serverKey;
    protected bool $isProduction;

    public function __construct()
    {
        $this->serverKey = config('midtrans.server_key', '');
        $this->isProduction = config('midtrans.is_production', false);
    }

    /**
     * Create a Midtrans Snap token for subscription payment.
     */
    public function createSnapToken(array $params): ?string
    {
        if (empty($this->serverKey)) {
            Log::warning('Midtrans: Server key not configured.');
            return null;
        }

        \Midtrans\Config::$serverKey = $this->serverKey;
        \Midtrans\Config::$isProduction = $this->isProduction;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        try {
            return \Midtrans\Snap::getSnapToken($params);
        } catch (\Exception $e) {
            Log::error('Midtrans: Failed to create Snap token.', [
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Build transaction params for subscription renewal.
     */
    public function buildSubscriptionParams(
        string $orderId,
        int $amount,
        string $clinicName,
        string $planName,
        string $duration,
        array $customer
    ): array {
        return [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $amount,
            ],
            'item_details' => [
                [
                    'id' => 'SUB-' . strtoupper($planName),
                    'price' => $amount,
                    'quantity' => 1,
                    'name' => "Langganan {$planName} ({$duration})",
                ],
            ],
            'customer_details' => [
                'first_name' => $customer['name'] ?? '',
                'email' => $customer['email'] ?? '',
                'phone' => $customer['phone'] ?? '',
            ],
        ];
    }

    /**
     * Verify notification signature from Midtrans webhook.
     */
    public function verifyNotification(array $payload): bool
    {
        if (empty($this->serverKey)) {
            return false;
        }

        $orderId = $payload['order_id'] ?? '';
        $statusCode = $payload['status_code'] ?? '';
        $grossAmount = $payload['gross_amount'] ?? '';
        $serverKey = $this->serverKey;

        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        return ($payload['signature_key'] ?? '') === $expectedSignature;
    }
}
