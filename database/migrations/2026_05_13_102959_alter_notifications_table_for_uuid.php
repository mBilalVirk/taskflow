<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            // Drop the old bigint column
            $table->dropColumn('notifiable_id');
            
            // Add UUID compatible column
            $table->uuid('notifiable_id')->index();
        });
    }

    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn('notifiable_id');
            $table->foreignId('notifiable_id')->constrained()->onDelete('cascade');
        });
    }
};