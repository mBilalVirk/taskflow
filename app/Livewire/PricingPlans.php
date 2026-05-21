<?php

namespace App\Livewire;

use App\Models\Subscription;
use Livewire\Component;

class PricingPlans extends Component
{
    public ?string $selectedPlan = null;
    public bool $isAnnual = false;

    #[\Livewire\Attributes\Computed]
    public function plans()
    {
        return Subscription::plans();
    }

    public function selectPlan($plan)
    {
        if (auth()->check()) {
            $this->selectedPlan = $plan;
            $this->dispatch('planSelected', plan: $plan);
        } else {
            // Redirect to login
            return redirect()->route('login');
        }
    }

    public function render()
    {
        return view('livewire.pricing-plans');
    }
}