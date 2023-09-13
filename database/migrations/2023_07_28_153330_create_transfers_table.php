<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();

            $table->enum('from_type' , [
                'admin',
                'client',
                'delivery',
                'chef',
            ]);
            $table->unsignedBigInteger('from_id')->nullable();

            $table->enum('to_type' , [
                'admin',
                'client',
                'delivery',
                'chef',
            ]);

            $table->unsignedBigInteger('to_id')->nullable();
            $table->unsignedDecimal('amount' , 10, 2)->default(0);


            $table->foreign('from_id')->references('id')->on('users')
                ->onUpdate('cascade')
                ->nullOnDelete() ;

            $table->foreign('to_id')->references('id')->on('users')
                ->onUpdate('cascade')
                ->nullOnDelete() ;

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
