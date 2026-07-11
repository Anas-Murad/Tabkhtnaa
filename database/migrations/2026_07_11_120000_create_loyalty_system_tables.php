<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('loyalty_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('points_per_dinar')->default(1);
            $table->integer('min_redemption_points')->default(100);
            $table->integer('points_expiry_months')->default(12);
            $table->integer('referral_referrer_points')->default(100);
            $table->integer('referral_referred_points')->default(50);
            $table->boolean('enable_points_system')->default(true);
            $table->boolean('enable_min_redemption')->default(true);
            $table->boolean('enable_expiry')->default(true);
            $table->boolean('enable_auto_redemption')->default(false);
            $table->boolean('enable_double_points')->default(true);
            $table->boolean('enable_tiers')->default(true);
            $table->boolean('enable_welcome_bonus')->default(true);
            $table->boolean('enable_birthday_bonus')->default(true);
            $table->boolean('enable_referral')->default(true);
            $table->integer('welcome_bonus_points')->default(100);
            $table->integer('birthday_bonus_points')->default(150);
            $table->timestamps();
        });

        Schema::create('loyalty_tiers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('level');
            $table->decimal('min_lifetime_spending', 12, 2);
            $table->decimal('points_multiplier', 3, 2)->default(1.00);
            $table->integer('min_redemption_points')->nullable();
            $table->decimal('birthday_bonus_multiplier', 3, 2)->default(1.00);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->integer('total_points')->default(0)->after('can_delivery');
            $table->decimal('lifetime_spending', 12, 2)->default(0)->after('total_points');
            $table->string('current_tier')->default('Regular')->after('lifetime_spending');
            $table->string('referral_code')->nullable()->unique()->after('current_tier');
            $table->foreignId('referred_by')->nullable()->after('referral_code')->constrained('users')->nullOnDelete();
            $table->boolean('welcome_bonus_awarded')->default(false)->after('referred_by');
        });

        Schema::create('user_tier_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('old_tier');
            $table->string('new_tier');
            $table->string('reason')->nullable();
            $table->timestamps();
        });

        Schema::create('loyalty_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('points');
            $table->enum('type', ['earn', 'redeem', 'bonus', 'expiry', 'welcome', 'birthday', 'referral']);
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'created_at']);
            $table->index('type');
        });

        Schema::create('double_points_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->decimal('multiplier', 3, 2)->default(2.00);
            $table->enum('applies_to', ['all', 'delivery', 'pick_up'])->default('all');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('redemptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('points_used');
            $table->decimal('amount_discounted', 10, 2);
            $table->enum('status', ['pending', 'applied', 'rejected', 'cancelled'])->default('applied');
            $table->timestamps();
        });

        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referrer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('referred_id')->constrained('users')->cascadeOnDelete();
            $table->integer('points_awarded_to_referrer')->default(0);
            $table->integer('points_awarded_to_referred')->default(0);
            $table->timestamps();
            $table->unique(['referrer_id', 'referred_id']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->integer('points_earned')->default(0)->after('total');
            $table->integer('points_redeemed')->default(0)->after('points_earned');
            $table->boolean('loyalty_points_awarded')->default(false)->after('points_redeemed');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['points_earned', 'points_redeemed', 'loyalty_points_awarded']);
        });

        Schema::dropIfExists('referrals');
        Schema::dropIfExists('redemptions');
        Schema::dropIfExists('double_points_campaigns');
        Schema::dropIfExists('loyalty_transactions');
        Schema::dropIfExists('user_tier_histories');

        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('referred_by');
            $table->dropColumn([
                'total_points',
                'lifetime_spending',
                'current_tier',
                'referral_code',
                'welcome_bonus_awarded',
            ]);
        });

        Schema::dropIfExists('loyalty_tiers');
        Schema::dropIfExists('loyalty_settings');
    }
};
