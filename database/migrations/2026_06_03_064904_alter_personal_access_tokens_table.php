<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            // 1. Drop the polymorphic morphs columns ('tokenable_id' and 'tokenable_type')
            $table->dropMorphs('tokenable');

            // 2. Add the clean UUID column right after the ID
            $table->uuid('user_id')->after('id');

            // 3. Set up the foreign key constraint pointing to users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // 4. Modify the name and token columns to match your target blueprint
            $table->string('name')->change();
            $table->text('token')->change(); // Changes from string(64) to text

            // 5. Drop the index on expires_at if you want to match perfectly
            $table->dropIndex(['expires_at']); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('personal_access_tokens', function (Blueprint $table) {
            // Re-add the index on expires_at
            $table->index(['expires_at']);

            // Revert column types
            $table->text('name')->change();
            $table->string('token', 64)->change();

            // Drop foreign key and UUID column
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');

            // Re-create the polymorphic morphs columns
            $table->morphs('tokenable');
        });
    }
};