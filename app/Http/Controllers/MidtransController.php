<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    /**
     * Handle Midtrans payment notification webhook.
     */
    public function notification(Request $request, MidtransService $midtrans): \Illuminate\Http\JsonResponse
    {
        $payload = $request->all();

        Log::info('Midtrans notification received', ['order_id' => $payload['order_id'] ?? 'N/A']);

        // Verify signature
        if (!$midtrans->verifyNotification($payload)) {
            Log::warning('Midtrans: Invalid signature', $payload);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $orderId = $payload['order_id'] ?? '';
        $transactionStatus = $payload['transaction_status'] ?? '';
        $transactionId = $payload['transaction_id'] ?? '';

        // Find the subscription by order_id
        $subscription = Subscription::where('midtrans_order_id', $orderId)->first();

        if (!$subscription) {
            Log::warning("Midtrans: Subscription not found for order {$orderId}");
            return response()->json(['message' => 'Not found'], 404);
        }

        // Handle transaction status
        if (in_array($transactionStatus, ['capture', 'settlement'])) {
            $subscription->update([
                'status' => 'active',
                'midtrans_transaction_id' => $transactionId,
            ]);

            // Re-activate clinic if needed
            $subscription->clinic->update(['status' => 'active']);

            Log::info("Midtrans: Subscription #{$subscription->id} activated via {$transactionStatus}");

        } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'])) {
            $subscription->update([
                'status' => 'failed',
                'midtrans_transaction_id' => $transactionId,
            ]);

            Log::info("Midtrans: Subscription #{$subscription->id} failed: {$transactionStatus}");

        } elseif ($transactionStatus === 'pending') {
            $subscription->update([
                'status' => 'pending',
                'midtrans_transaction_id' => $transactionId,
            ]);
        }

        return response()->json(['message' => 'OK']);
    }
}
