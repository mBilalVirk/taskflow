<?php

namespace App\Livewire;

use App\Models\Team;
use App\Services\StripeService;
use Livewire\Component;
use Illuminate\Http\Request;

class CheckoutComponent extends Component
{
    public Team $team;
    public string $plan;
    public string $status = '';
    public bool $isProcessing = false;

    #[\Livewire\Attributes\Computed]
    public function planDetails()
    {
        return \App\Models\Subscription::planDetails($this->plan);
    }

    public function mount(Team $team, Request $request)
    {
        $this->team = $team;
        // Get plan from URL query parameter
    $planFromUrl = $request->query('plan');
    
    // Validate plan is one of the allowed values
    $allowedPlans = array_keys(\App\Models\Subscription::plans());
    
    if ($planFromUrl && in_array($planFromUrl, $allowedPlans)) {
        $this->plan = $planFromUrl;
    }
    }

    public function checkout()
    {
        $this->isProcessing = true;

        try {
            $stripeService = new StripeService();
            $session = $stripeService->createCheckoutSession($this->team, $this->plan);

            return redirect($session->url);
        } catch (\Exception $e) {
            $this->status = 'error: ' . $e->getMessage();
            $this->isProcessing = false;
        }
    }

    public function render()
    {
        return view('livewire.checkout-component');
    }
}