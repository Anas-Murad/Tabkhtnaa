<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();


            $table->unsignedBigInteger('user1_id')->nullable();
            $table->foreign('user1_id')->references('id')->on('users')
                ->onUpdate('cascade')
                ->nullOnDelete() ;

            $table->unsignedBigInteger('user2_id')->nullable();
            $table->foreign('user2_id')->references('id')->on('users')
                ->onUpdate('cascade')
                ->nullOnDelete() ;


            $table->enum('user1_type'  ,['client','delivery','chef' ,'admin']);
            $table->enum('user2_type'  ,['client','delivery','chef' ,'admin']);


            $table->dateTime('user1_deleted_at')->nullable();
            $table->dateTime('user2_deleted_at')->nullable();

            $table->unsignedBigInteger('order_id')->nullable();
            $table->foreign('order_id')->references('id')->on('orders')
                ->onUpdate('cascade')
                ->nullOnDelete() ;

            $table->unsignedBigInteger('last_message_id')->nullable();


            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
