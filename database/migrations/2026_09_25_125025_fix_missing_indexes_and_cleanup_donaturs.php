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
        // 1. Donations: add missing unique and indexes
        Schema::table('donations', function (Blueprint $table) {
            // Add unique index to invoice
            $table->unique('invoice');
            
            // Add index to status
            $table->index('status');
            
            // Add composite index for performance
            $table->index(['campaign_id', 'status']);
        });

        // 2. Donaturs: cleanup legacy columns (password, email_verified_at, remember_token)
        Schema::table('donaturs', function (Blueprint $table) {
            $table->dropColumn(['email_verified_at', 'password', 'remember_token']);
        });

        // 3. Foreign Keys: ensure FK constraints are set properly for performance and data integrity
        // DB might not have FK constraints if created with migration batch 1
        Schema::table('campaigns', function (Blueprint $table) {
             // We can't easily modify FKs in some DBs without dropping first, 
             // but let's ensure the type is consistent.
             $table->unsignedBigInteger('category_id')->change();
             $table->unsignedBigInteger('user_id')->change();
        });

        Schema::table('donations', function (Blueprint $table) {
             $table->unsignedBigInteger('campaign_id')->change();
             $table->unsignedBigInteger('donatur_id')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropUnique(['invoice']);
            $table->dropIndex(['status']);
            $table->dropIndex(['campaign_id', 'status']);
        });

        Schema::table('donaturs', function (Blueprint $table) {
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
        });
    }
};
