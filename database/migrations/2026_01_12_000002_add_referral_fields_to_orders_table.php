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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('referral_code', 32)->nullable()->after('source');
            $table->foreignId('affiliate_referral_id')
                ->nullable()
                ->after('referral_code')
                ->constrained('affiliate_referrals')
                ->nullOnDelete();

            $table->index('referral_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['affiliate_referral_id']);
            $table->dropColumn(['affiliate_referral_id', 'referral_code']);
        });
    }
};
