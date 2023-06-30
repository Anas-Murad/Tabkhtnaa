<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->unsignedBigInteger('chef_id');
            $table->foreign('chef_id')->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->enum('rating_chef' , ['1' , '2' , '3' , '4' , '5'])->nullable();
            $table->enum('rating_delivery' , ['1' , '2' , '3' , '4' , '5'])->nullable();
            $table->enum('rating_speed_chef' , ['1' , '2' , '3' , '4' , '5'])->nullable();
            $table->enum('rating_speed_delivery' , ['1' , '2' , '3' , '4' , '5'])->nullable();
            $table->string('note')->nullable();
            $table->string('photo')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ratings');
    }
};
