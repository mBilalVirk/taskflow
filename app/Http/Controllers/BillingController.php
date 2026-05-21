<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Services\StripeService;
use App\Services\BillingService;
use Illuminate\Http\Request;

class BillingController extends Controller
{
    public function __construct(
        protected StripeService $stripeService,
        protected BillingService $billingService,
    ) {}

    public function success(Request $request, Team $team)
    {
        $sessionId = $request->query('session_id');
        
        if (!$sessionId) {
            return redirect()->route('billing.dashboard', $team);
        }

        try {
            $session = \Stripe\Checkout\Session::retrieve($sessionId);
            
            if ($session->payment_status === 'paid') {
                $subscription = $this->stripeService->getSubscription($session->subscription);
                $this->billingService->upgradePlan($team, $session->metadata['plan'], $subscription);
                
                return redirect()->route('billing.dashboard', $team)->with('success', 'Subscription updated!');
            }
        } catch (\Exception $e) {
            return redirect()->route('billing.checkout', $team)->with('error', $e->getMessage());
        }
    }

    public function handleWebhook(Request $request)
    {
        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sig_header,
                config('services.stripe.webhook_secret')
            );
        } catch (\UnexpectedValueException $e) {
            return response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return response('Invalid signature', 400);
        }

        // Handle the event
        match ($event->type) {
            'customer.subscription.updated' => $this->handleSubscriptionUpdated($event),
            'customer.subscription.deleted' => $this->handleSubscriptionDeleted($event),
            'invoice.paid' => $this->handleInvoicePaid($event),
            'invoice.created' => $this->handleInvoiceCreated($event),
            default => null,
        };

        return response('Webhook handled', 200);
    }

    protected function handleSubscriptionUpdated($event)
    {
        $subscription = $event->data->object;
        $team = Team::where('id', $subscription->metadata['team_id'] ?? null)->first();

        if ($team) {
            $team->subscription?->update([
                'stripe_status' => $subscription->status,
                'current_period_start' => $subscription->current_period_start,
                'current_period_end' => $subscription->current_period_end,
            ]);
        }
    }

    protected function handleSubscriptionDeleted($event)
    {
        $subscription = $event->data->object;
        $team = Team::where('id', $subscription->metadata['team_id'] ?? null)->first();

        if ($team) {
            $this->billingService->reactivateFreePlan($team);
        }
    }

    protected function handleInvoicePaid($event)
    {
        $invoice = $event->data->object;
        $team = Team::where('stripe_customer_id', $invoice->customer)->first();

        if ($team) {
            $this->stripeService->syncInvoice($team, $invoice->id);
        }
    }

    protected function handleInvoiceCreated($event)
    {
        $invoice = $event->data->object;
        $team = Team::where('stripe_customer_id', $invoice->customer)->first();

        if ($team) {
            $this->stripeService->syncInvoice($team, $invoice->id);
        }
    }
}