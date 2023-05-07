<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('additions', function (Blueprint $table) {

            $table->id();
            $table->string('name');
            $table->unsignedDecimal('price', 10, 2)->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('addition_category_id');

            $table->foreign('addition_category_id')->references('id')->on('addition_categories')
                ->onUpdate('cascade')
                ->onDelete('cascade') ;

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('additions');
    }
};
