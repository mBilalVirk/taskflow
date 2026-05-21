<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            // Primary Key
            $table->uuid('id')->primary();
            
            // Foreign Key - Team (unique: only one subscription per team)
            $table->uuid('team_id')->unique();
            $table->foreign('team_id')
                ->references('id')
                ->on('teams')
                ->onDelete('cascade');
            
            // Stripe Data
            $table->string('stripe_subscription_id')->unique();
            $table->string('stripe_customer_id')->unique();
            $table->string('stripe_price_id')->nullable();
            
            // Subscription Details
            $table->string('status'); // active, past_due, canceled, incomplete
            $table->string('plan'); // free, pro, enterprise
            $table->integer('price'); // in cents: 2900 = $29.00
            $table->integer('members_limit'); // max team members for plan
            
            // Billing Period
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('current_period_start')->nullable();
            $table->timestamp('current_period_end')->nullable();
            $table->timestamp('canceled_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            
            // Payment Method
            $table->string('payment_method')->nullable();
            $table->string('last_four')->nullable(); // last 4 digits of card
            
            // Metadata
            $table->json('metadata')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('team_id');
            $table->index('status');
            $table->index('plan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};