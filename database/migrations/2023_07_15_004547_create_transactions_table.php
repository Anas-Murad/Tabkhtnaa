<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onUpdate('cascade')
                ->nullOnDelete() ;



            $table->unsignedBigInteger('order_id')->nullable();
            $table->foreign('order_id')
                ->references('id')
                ->on('orders')
                ->onUpdate('cascade')
                ->nullOnDelete() ;




            $table->string('payment_id')->nullable();
            $table->string('payment_method')->nullable();
            $table->enum('service_type' , [
                'order',
                'charging_wallet',
                'to_admin',
            ])->nullable();

            $table->unsignedDecimal('amount' , 10, 2)->nullable();
            $table->string('currency')->nullable();

            $table->enum('status' ,[
                'pending',
                'success',
                'failed',
            ])->default('pending');



            $table->enum('admin_status' ,[
                'pending',
                'success',
                'failed',
            ])->default('pending');

            $table->string('admin_notes')->nullable();
            $table->longText('tracking_data')->nullable();
            $table->longText('webhook')->nullable();
            $table->boolean('tried_again')
                ->default(false)
                ->comment('If the payment fails, it will be retried with a different payment process Note that this process has been retried to set it as a non-essential process');
            $table->softDeletes();
            $table->timestamps();


        });
    }
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
