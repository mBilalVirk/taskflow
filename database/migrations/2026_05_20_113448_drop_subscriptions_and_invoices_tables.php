<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    
    public function up(): void
    {
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('invoices');
    }

    public function down(): void
    {
        // Optional: recreate tables if rollback happens
        Schema::create('subscriptions', function ($table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('invoices', function ($table) {
            $table->id();
            $table->timestamps();
        });
    }
};