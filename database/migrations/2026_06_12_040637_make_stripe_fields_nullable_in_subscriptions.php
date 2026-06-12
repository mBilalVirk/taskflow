<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('stripe_subscription_id')->nullable()->change();
            $table->string('stripe_customer_id')->nullable()->change();
            $table->decimal('price', 10, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('stripe_subscription_id')->nullable(false)->change();
            $table->string('stripe_customer_id')->nullable(false)->change();
            $table->decimal('price', 10, 2)->nullable(false)->change();
        });
    }
};