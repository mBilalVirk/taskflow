<?php

namespace App\Livewire;

use App\Models\Team;
use App\Services\BillingService;
use Livewire\Component;
use Livewire\Attributes\Computed;

class BillingDashboard extends Component
{
    public Team $team;
    protected $billingService;

    public function mount(Team $team)
    {
        $this->team = $team;
        $this->billingService = app(BillingService::class);
    }

    #[Computed]
    public function subscriptionStatus()
    {
        // dd($this->billingService->getSubscriptionStatus($this->team));
        // \Log::debug('getSubscriptionStatus', [
        //     'team_id' => $this->team->id,
        //     'subscription' => $this->team->subscription?->toArray(),
        // ]);
        $this->billingService = app(BillingService::class);
        return $this->billingService->getSubscriptionStatus($this->team);
    }

    #[Computed]
    public function invoices()
    {
        return $this->team->invoices()
            ->latest()
            ->limit(10)
            ->get();
    }

    #[Computed]
    public function availablePlans()
    {
        return \App\Models\Subscription::plans();
    }

    public function upgradeToPro()
    {
        return redirect()->route('billing.checkout', ['team' => $this->team, 'plan' => 'pro']);
    }

    public function upgradeToEnterprise()
    {
        return redirect()->route('billing.checkout', ['team' => $this->team, 'plan' => 'enterprise']);
    }

    public function cancelSubscription()
    {
        if ($this->subscriptionStatus()['is_free']) {
            return;
        }

        $this->billingService->cancelSubscription($this->team);
        $this->dispatch('subscriptionCanceled');
    }

    public function render()
    {
        return view('livewire.billing-dashboard');
    }
}