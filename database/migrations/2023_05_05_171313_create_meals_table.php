<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('meals', function (Blueprint $table) {


            $table->id();
            $table->unsignedBigInteger('category_id');
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('code');
            $table->text('description')->nullable();
            $table->unsignedDecimal('price', 10, 2)->nullable();
            $table->string('image')->nullable();
            $table->enum('type' , ['pre-order' , 'ready']) ->default('ready');
            $table->boolean('is_active')->default(true);
            $table->text('days')->nullable();


            $table->enum('admin_status' , ['new' , 'confirmed' ,  'disabled'])->default('new');
            $table->string('admin_note')->nullable( );

            $table->string('preparation_time')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('category_id')->references('id')->on('categories')
                ->onUpdate('cascade')
                ->onDelete('cascade') ;

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meals');
    }
};
