<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_history_deliveries', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('order_id')->nullable();
            $table->foreign('order_id')->references('id')->on('orders')
                ->onUpdate('cascade')
                ->nullOnDelete() ;


            $table->unsignedBigInteger('delivery_id')->nullable();
            $table->foreign('delivery_id')->references('id')->on('users')
                ->onUpdate('cascade')
                ->nullOnDelete() ;

            $table->enum('status' ,[
                'pending', // جديد
                'accepted', // تم قبول الطلب
                'rejected', // تم الرفض
            ])->default('pending');
            $table->string('rejected_reason')->nullable();


            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_history_deliveries');
    }
};
