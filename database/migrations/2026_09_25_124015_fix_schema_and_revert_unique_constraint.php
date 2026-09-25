<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Categories: Add softDeletes if missing
        if (!Schema::hasColumn('categories', 'deleted_at')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // 2. Campaigns: Add current_donation, softDeletes, change max_date to dateTime if date
        Schema::table('campaigns', function (Blueprint $table) {
            if (!Schema::hasColumn('campaigns', 'current_donation')) {
                $table->unsignedBigInteger('current_donation')->default(0)->after('target_donation');
            }
            if (!Schema::hasColumn('campaigns', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        // 3. Donaturs: Add softDeletes
        if (!Schema::hasColumn('donaturs', 'deleted_at')) {
            Schema::table('donaturs', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // 4. Donations: Add paid_at, index on status and composite index
        Schema::table('donations', function (Blueprint $table) {
            if (!Schema::hasColumn('donations', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('status');
            }
        });

        // 5. Sliders: Add is_active, order with unique constraint
        Schema::table('sliders', function (Blueprint $table) {
            if (!Schema::hasColumn('sliders', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('link');
            }
            if (!Schema::hasColumn('sliders', 'order')) {
                $table->integer('order')->default(0)->unique()->after('is_active');
            }
        });

        // Backfill current_donation in campaigns table from existing successful donations
        $campaigns = DB::table('campaigns')->get();
        foreach ($campaigns as $campaign) {
            $total = DB::table('donations')
                ->where('campaign_id', $campaign->id)
                ->where('status', 'success')
                ->sum('amount');

            DB::table('campaigns')
                ->where('id', $campaign->id)
                ->update(['current_donation' => $total]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn('current_donation');
            $table->dropSoftDeletes();
        });

        Schema::table('donaturs', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn('paid_at');
        });

        Schema::table('sliders', function (Blueprint $table) {
            $table->dropColumn(['is_active', 'order']);
        });
    }
};
