<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('meal_accessories', function (Blueprint $table) {
            $table->unsignedBigInteger('meal_id');
            $table->unsignedBigInteger('accessory_id');

            $table->foreign('meal_id')->references('id')->on('meals')
                ->onUpdate('cascade')
                ->onDelete('cascade') ;

            $table->foreign('accessory_id')->references('id')->on('accessories')
                ->onUpdate('cascade')
                ->onDelete('cascade') ;

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meal_accessories');
    }
};
