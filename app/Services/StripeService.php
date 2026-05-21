<?php

namespace App\Services;

use App\Models\Subscription;
use App\Models\Invoice;
use App\Models\Team;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\Price;
use Stripe\Checkout\Session;
use Stripe\Invoice as StripeInvoice;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Create or get Stripe customer
     */
    public function createCustomer(Team $team)
    {
        // Check if customer already exists
        $subscription = Subscription::where('team_id', $team->id)->first();
        
        if ($subscription && $subscription->stripe_customer_id) {
            return $subscription->stripe_customer_id;
        }

        // Create new customer
        $customer = Customer::create([
            'email' => $team->owner->email,
            'name' => $team->name,
            'metadata' => [
                'team_id' => $team->id,
            ],
        ]);

        return $customer->id;
    }

    /**
     * Get Stripe price ID for plan
     */
    public function getPriceId($plan)
    {
        return match($plan) {
            'pro' => config('services.stripe.pro_price_id'),
            'enterprise' => config('services.stripe.enterprise_price_id'),
            default => null,
        };
    }

    /**
     * Create checkout session
     */
    public function createCheckoutSession(Team $team, $plan)
    {
        $customerId = $this->createCustomer($team);
        $priceId = $this->getPriceId($plan);

        if (!$priceId) {
            throw new \Exception("Invalid plan: $plan");
        }

        $session = Session::create([
            'customer' => $customerId,
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price' => $priceId,
                'quantity' => 1,
            ]],
            'mode' => 'subscription',
            'success_url' => route('billing.success', ['team' => $team->id]) . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('billing.checkout', ['team' => $team->id]),
            'metadata' => [
                'team_id' => $team->id,
                'plan' => $plan,
            ],
        ]);

        return $session;
    }

    /**
     * Get subscription from Stripe
     */
    public function getSubscription($stripeSubscriptionId)
    {
        return \Stripe\Subscription::retrieve($stripeSubscriptionId);
    }

    /**
     * Update subscription plan
     */
    public function updateSubscriptionPlan(Subscription $subscription, $newPlan)
    {
        $stripeSubscription = $this->getSubscription($subscription->stripe_subscription_id);
        
        $newPriceId = $this->getPriceId($newPlan);

        $updated = \Stripe\Subscription::update(
            $subscription->stripe_subscription_id,
            [
                'items' => [
                    [
                        'id' => $stripeSubscription->items->data[0]->id,
                        'price' => $newPriceId,
                    ],
                ],
                'proration_behavior' => 'create_prorations',
            ]
        );

        return $updated;
    }

    /**
     * Cancel subscription
     */
    public function cancelSubscription(Subscription $subscription)
    {
        $canceled = \Stripe\Subscription::update(
            $subscription->stripe_subscription_id,
            ['cancel_at_period_end' => true]
        );

        return $canceled;
    }

    /**
     * Retrieve invoices
     */
    public function getInvoices($stripeCustomerId)
    {
        return StripeInvoice::all([
            'customer' => $stripeCustomerId,
            'limit' => 100,
        ]);
    }

    /**
     * Sync invoice from Stripe
     */
    public function syncInvoice($team, $stripeInvoiceId)
    {
        $stripeInvoice = StripeInvoice::retrieve($stripeInvoiceId);

        return Invoice::updateOrCreate(
            ['stripe_invoice_id' => $stripeInvoiceId],
            [
                'team_id' => $team->id,
                'stripe_customer_id' => $stripeInvoice->customer,
                'amount' => $stripeInvoice->total,
                'currency' => $stripeInvoice->currency,
                'status' => $stripeInvoice->status,
                'plan' => $stripeInvoice->metadata['plan'] ?? 'unknown',
                'pdf_url' => $stripeInvoice->pdf,
                'hosted_invoice_url' => $stripeInvoice->hosted_invoice_url,
                'paid_at' => $stripeInvoice->paid ? now() : null,
                'due_date' => $stripeInvoice->due_date ? now()->timestamp($stripeInvoice->due_date) : null,
                'description' => $stripeInvoice->description,
            ]
        );
    }
}