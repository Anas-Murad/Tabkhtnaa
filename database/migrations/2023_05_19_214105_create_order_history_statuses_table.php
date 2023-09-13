<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_history_statuses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id')->nullable();
            $table->foreign('order_id')->references('id')->on('orders')
                ->onUpdate('cascade')
                ->nullOnDelete() ;


            $table->enum('status' ,[
                'pending', // جديد
                'confirmed', // تم قبول الطلب
                'on_way',//في الطريق
                'delivered',//تم التوصيل
                'not_delivered',//لم يتم التوصيل
                'rejected', // تم الرفض
                'cancel', // ملغي
            ])->default('pending');

            $table->enum('action_by_type' , ['client','delivery','chef']);
            $table->unsignedBigInteger('action_by_id')->nullable();
            $table->foreign('action_by_id')->references('id')->on('users')
                ->onUpdate('cascade')
                ->nullOnDelete() ;

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_hestory_statuses');
    }
};
