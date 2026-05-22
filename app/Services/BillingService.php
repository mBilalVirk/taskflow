<?php

namespace App\Services;

use App\Models\Subscription;
use App\Models\Team;
use App\Services\StripeService;
use Illuminate\Support\Carbon;

class BillingService
{
    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    /**
     * Get or create free subscription for new team
     */
    public function createFreeSubscription(Team $team)
    {
        // Check if already exists
        if ($team->subscription) {
            return $team->subscription;
        }

        return Subscription::create([
            'team_id' => $team->id,
            'stripe_subscription_id' => 'free_' . $team->id,
            'stripe_customer_id' => 'free_' . $team->id,
            'status' => 'active',
            'plan' => 'free',
            'price' => 0,
            'members_limit' => 1,
        ]);
    }

    /**
     * Check if team can add more members
     */
    public function canAddMembers(Team $team): bool
    {
        $subscription = $team->subscription ?? $this->createFreeSubscription($team);

        // Free plan only allows 1 member
        if ($subscription->isFree()) {
            return $team->members()->count() < $subscription->members_limit;
        }

        // Pro allows 5, Enterprise unlimited
        if ($subscription->members_limit === null) {
            return true; // unlimited
        }

        return $team->members()->count() < $subscription->members_limit;
    }

    /**
     * Get available member slots
     */
    public function getAvailableMemberSlots(Team $team): ?int
    {
        $subscription = $team->subscription ?? $this->createFreeSubscription($team);

        if ($subscription->members_limit === null) {
            return null; // unlimited
        }

        return $subscription->members_limit - $team->members()->count();
    }

    /**
     * Upgrade team plan
     */
    public function upgradePlan(Team $team, $newPlan, $stripeSubscriptionData)
    {
        $subscription = $team->subscription ?? $this->createFreeSubscription($team);

        $item = $stripeSubscriptionData['items']['data'][0] ?? [];

        return $subscription->update([
            'stripe_subscription_id' => $stripeSubscriptionData['id'],
            'stripe_customer_id' => $stripeSubscriptionData['customer'],
            'stripe_price_id' => $item['price']['id'] ?? null,
            'status' => $stripeSubscriptionData['status'] ?? 'incomplete',
            'plan' => $newPlan,
            'price' => $item['price']['unit_amount'] ?? 0,
            'members_limit' => $this->getMembersLimitForPlan($newPlan),

            // ✅ Correct timestamps (now inside the item)
            'current_period_start' => isset($item['current_period_start']) ? \Carbon\Carbon::createFromTimestamp($item['current_period_start']) : now(),

            'current_period_end' => isset($item['current_period_end']) ? \Carbon\Carbon::createFromTimestamp($item['current_period_end']) : now()->addMonth(),

            'payment_method' => $stripeSubscriptionData['default_payment_method'] ?? null,
        ]);
    }

    /**
     * Get members limit for plan
     */
    public function getMembersLimitForPlan($plan): ?int
    {
        return match ($plan) {
            'free' => 1,
            'pro' => 5,
            'enterprise' => 99999,
            default => 1,
        };
    }

    /**
     * Cancel subscription
     */
    public function cancelSubscription(Team $team)
    {
        $subscription = $team->subscription;

        if (!$subscription || $subscription->isFree()) {
            return null;
        }

        $this->stripeService->cancelSubscription($subscription);

        return $subscription->update([
            'status' => 'canceled',
            'canceled_at' => now(),
        ]);
    }

    /**
     * Reactivate free plan after cancellation
     */
    public function reactivateFreePlan(Team $team)
    {
        // Create free subscription
        return Subscription::updateOrCreate(
            ['team_id' => $team->id],
            [
                'stripe_subscription_id' => 'free_' . $team->id,
                'status' => 'active',
                'plan' => 'free',
                'price' => 0,
                'members_limit' => 1,
                'canceled_at' => null,
            ],
        );
    }

    /**
     * Get subscription status for team
     */
    public function getSubscriptionStatus(Team $team)
    {
        $subscription = $team->subscription ?? $this->createFreeSubscription($team);
    //     dd([
    //     'plan' => $subscription->plan,
    //         'price' => $subscription->getPriceFormatted(),
    //         'status' => $subscription->status,
    //         'is_active' => $subscription->isActive(),
    //         'is_free' => $subscription->isFree(),
    //         'members_limit' => $subscription->members_limit,
    //         'current_members' => $team->members()->count(),
    //         'available_slots' => $this->getAvailableMemberSlots($team),
    //         'renews_at' => $subscription->renewsAt(),
    //         'on_trial' => $subscription->onTrial(),
    //         'trial_ends_at' => $subscription->trial_ends_at,
    // ]);

        return [
            'plan' => $subscription->plan,
            'price' => $subscription->getPriceFormatted(),
            'status' => $subscription->status,
            'is_active' => $subscription->isActive(),
            'is_free' => $subscription->isFree(),
            'members_limit' => $subscription->members_limit,
            'current_members' => $team->members()->count(),
            'available_slots' => $this->getAvailableMemberSlots($team),
            'renews_at' => $subscription->renewsAt(),
            'on_trial' => $subscription->onTrial(),
            'trial_ends_at' => $subscription->trial_ends_at,
        ];
    }
}
