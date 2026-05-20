<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Creates activity_logs table for tracking all user actions
     * Stores detailed information about what was done, when, and by whom
     */
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            /**
             * PRIMARY KEY & IDENTITY
             */
            $table->uuid('id')->primary();
            $table->unique('id');
            
            /**
             * FOREIGN KEYS - References to other tables
             * ⚠️ IMPORTANT: user_id and team_id must be UUID to match parent tables
             */
            // References users table
            $table->uuid('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            
            // References teams table
            $table->uuid('team_id');
            $table->foreign('team_id')
                ->references('id')
                ->on('teams')
                ->onDelete('cascade');
            
            /**
             * ACTIVITY TYPE & SUBJECT
             */
            // What action was performed: created, updated, deleted, commented, assigned, etc.
            $table->string('action', 50);
            
            // What type of object: Task, Project, Team, TeamMember, TaskComment, etc.
            $table->string('subject_type', 100);
            
            // UUID ID of the subject (the object that was acted upon)
            $table->uuid('subject_id')->nullable();
            
            /**
             * ACTIVITY DETAILS
             */
            // Human-readable description of the activity
            // Example: "John created task: Build login form"
            $table->text('description')->nullable();
            
            // JSON field storing before/after values for updates
            // Example: {"status": ["todo", "in_progress"], "priority": ["medium", "high"]}
            $table->json('changes')->nullable();
            
            /**
             * METADATA
             */
            // IP address of the user who performed the action
            $table->string('ip_address', 45)->nullable();
            
            // User agent string (browser/client information)
            $table->text('user_agent')->nullable();
            
            // Additional data stored as JSON
            $table->json('metadata')->nullable();
            
            /**
             * TIMESTAMPS
             */
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            
            /**
             * INDEXES FOR PERFORMANCE
             */
            // Find activities by user
            $table->index('user_id');
            
            // Find activities by team
            $table->index('team_id');
            
            // Find activities by type and subject
            $table->index(['subject_type', 'subject_id']);
            
            // Find activities by action type
            $table->index('action');
            
            // Sort by creation date efficiently
            $table->index('created_at');
            
            // Combined index for common queries
            $table->index(['team_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
            
            /**
             * COMPOSITE INDEXES
             */
            // Efficiently find all activities by user in a team
            $table->index(['team_id', 'user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};