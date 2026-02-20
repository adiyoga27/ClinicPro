<?php

namespace App\Livewire\Admin;

use App\Models\Subscription;
use App\Services\MidtransService;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.app')]
class SubscriptionManager extends Component
{
    public string $selectedDuration = '1_month';
    public ?string $snapToken = null;
    public bool $showPaymentModal = false;

    public function getDurationOptions(): array
    {
        return [
            '1_month' => ['label' => '1 Bulan', 'months' => 1, 'discount' => 0],
            '6_months' => ['label' => '6 Bulan', 'months' => 6, 'discount' => 10],
            '1_year' => ['label' => '1 Tahun', 'months' => 12, 'discount' => 20],
        ];
    }

    public function getActiveSubscription(): ?Subscription
    {
        $clinic = auth()->user()->clinic;
        return $clinic?->subscriptions()
            ->where('status', 'active')
            ->latest()
            ->first();
    }

    public function getCurrentPlan(): string
    {
        return $this->getActiveSubscription()?->plan ?? 'basic';
    }

    public function calculatePrice(): int
    {
        $plan = $this->getCurrentPlan();
        $prices = Subscription::planPrices();
        $monthlyPrice = $prices[$plan] ?? $prices['basic'];

        $duration = $this->getDurationOptions()[$this->selectedDuration] ?? $this->getDurationOptions()['1_month'];
        $total = $monthlyPrice * $duration['months'];

        // Apply discount
        if ($duration['discount'] > 0) {
            $total = (int) ($total * (1 - $duration['discount'] / 100));
        }

        return $total;
    }

    public function initiatePayment(): void
    {
        $clinic = auth()->user()->clinic;
        $user = auth()->user();
        $plan = $this->getCurrentPlan();
        $duration = $this->getDurationOptions()[$this->selectedDuration];
        $amount = $this->calculatePrice();

        $orderId = 'SUB-' . $clinic->id . '-' . now()->format('YmdHis') . '-' . random_int(100, 999);

        // Calculate new expiry date
        $currentSub = $this->getActiveSubscription();
        $startFrom = ($currentSub && $currentSub->expired_at->isFuture())
            ? $currentSub->expired_at
            : now();
        $expiredAt = $startFrom->copy()->addMonths($duration['months']);

        // Create pending subscription record
        $subscription = Subscription::create([
            'clinic_id' => $clinic->id,
            'plan' => $plan,
            'price' => $amount,
            'started_at' => $startFrom,
            'expired_at' => $expiredAt,
            'status' => 'pending',
            'midtrans_order_id' => $orderId,
        ]);

        // Generate Snap token
        $midtrans = app(MidtransService::class);
        $params = $midtrans->buildSubscriptionParams(
            $orderId,
            $amount,
            $clinic->name,
            ucfirst($plan),
            $duration['label'],
            [
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone ?? '',
            ]
        );

        $this->snapToken = $midtrans->createSnapToken($params);

        if ($this->snapToken) {
            $this->showPaymentModal = true;
        } else {
            session()->flash('error', 'Gagal membuat token pembayaran. Pastikan Midtrans server key sudah dikonfigurasi.');
        }
    }

    public function render()
    {
        $subscription = $this->getActiveSubscription();
        $clinic = auth()->user()->clinic;

        $daysLeft = 0;
        $totalDays = 1;
        $percentLeft = 0;

        if ($subscription && $subscription->expired_at) {
            $daysLeft = max(0, (int) now()->diffInDays($subscription->expired_at, false));
            $totalDays = max(1, (int) $subscription->started_at->diffInDays($subscription->expired_at));
            $percentLeft = min(100, max(0, ($daysLeft / $totalDays) * 100));
        }

        return view('livewire.admin.subscription-manager', [
            'subscription' => $subscription,
            'clinic' => $clinic,
            'daysLeft' => $daysLeft,
            'totalDays' => $totalDays,
            'percentLeft' => $percentLeft,
            'durationOptions' => $this->getDurationOptions(),
            'calculatedPrice' => $this->calculatePrice(),
        ]);
    }
}
