<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            // Primary Key
            $table->uuid('id')->primary();
            
            // Foreign Key
            $table->uuid('team_id');
            $table->foreign('team_id')
                ->references('id')
                ->on('teams')
                ->onDelete('cascade');
            
            // Stripe Data
            $table->string('stripe_invoice_id')->unique();
            $table->string('stripe_customer_id');
            
            // Invoice Details
            $table->integer('amount'); // in cents
            $table->string('currency')->default('usd');
            $table->string('status'); // draft, open, paid, uncollectible, void
            $table->string('plan'); // free, pro, enterprise
            
            // PDF & URLs
            $table->text('pdf_url')->nullable();
            $table->text('hosted_invoice_url')->nullable();
            
            // Payment Details
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('due_date')->nullable();
            $table->text('description')->nullable();
            
            // Metadata
            $table->json('metadata')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index('team_id');
            $table->index('status');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};