<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('chef_id')->nullable()->comment('Associates with the chef ID in users table');
            $table->unsignedBigInteger('delivery_id')->nullable()->comment('Associates with the delivery ID in users table');


            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')
                ->nullOnDelete() ;


            $table->foreign('chef_id')->references('id')->on('users')
                ->onUpdate('cascade')
                ->nullOnDelete() ;


            $table->foreign('delivery_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->nullOnDelete();


            $table->unsignedDecimal('delivery_fees', 10, 2)->nullable();
            $table->unsignedDecimal('tax', 10, 2)->nullable();
            $table->unsignedDecimal('sub_total', 10, 2)->nullable();
            $table->unsignedDecimal('discount', 10, 2)->nullable();
            $table->unsignedDecimal('total', 10, 2)->nullable();



            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->unsignedBigInteger('refund_id')->nullable();


            $table->string('coupon')->nullable();
            $table->text('details')->nullable();


            $table->enum('delivery_type' ,[
                'delivery',
                'pick_up',
                'chef_delivery',
            ])->default('delivery');



            $table->enum('payment_method' ,[
                'wallet',
                'cash',
                'cards',
            ])->default('cash');


            $table->smallInteger('expected_order_time')->nullable()->comment('in minutes');
            $table->smallInteger('estimated_delivery_time')->nullable()->comment('in minutes');
            $table->smallInteger('estimated_time')->nullable()->comment('in minutes');


            $table->enum('status' ,[
                'pending', // جديد
                'confirmed', // تم قبول الطلب
                'prepare', // قيد التحضير
                'prepared', // تم التحضير بانتظار التوصيل
                'on_way',//في الطريق
                'delivered',//تم التوصيل
                'not_delivered',//لم يتم التوصيل
                'rejected', // تم الرفض
                'cancel', // ملغي
            ])->default('pending');

            $table->enum('transaction_status' ,[
                'pending', // بالانتظار
                'success', // تم الدفع
                'cancel', // الرفض
            ])->default('pending');

            $table->string('rejected_reason')->nullable();


            $table->softDeletes();
            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
