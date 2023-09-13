<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transfer_records', function (Blueprint $table) {
            $table->id();
            $table->enum('to_type' , [
                'admin',
                'client',
                'delivery',
                'chef',
            ]);


            $table->unsignedBigInteger('to_id')->nullable();
            $table->foreign('to_id')->references('id')->on('users')
                ->onUpdate('cascade')
                ->nullOnDelete() ;


            $table->unsignedBigInteger('order_id')->nullable();
            $table->foreign('order_id')->references('id')->on('orders')
                ->onUpdate('cascade')
                ->nullOnDelete() ;

            $table->unsignedDecimal('amount' , 10, 2)->default(0);
            $table->unsignedDecimal('remainder' , 10, 2)->default(0);



            $table->unsignedBigInteger('transaction_id')->nullable();
            $table->foreign('transaction_id')->references('id')->on('transactions')
                ->onUpdate('cascade')
                ->nullOnDelete() ;

            $table->float('percent' , 5, 3)->unsigned()->nullable();



            $table->unsignedBigInteger('user_driver_cash_id')->nullable();
            $table->foreign('user_driver_cash_id')->references('id')->on('user_driver_cash')
                ->onUpdate('cascade')
                ->nullOnDelete() ;



            $table->boolean('admin_checked')->default(false);
            $table->text('admin_notes')->nullable();

            $table->enum('transfer_status' ,[
                'pending',
                'completed',
            ])->default('pending');


            $table->unsignedBigInteger('transfer_id')->nullable();
            $table->dateTime('transfer_date')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transfer_records');
    }
};
