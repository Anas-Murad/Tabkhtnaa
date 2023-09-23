<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('configurations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('country_id')->nullable();
            $table->foreign('country_id')->references('id')->on('countries')
                ->onUpdate('cascade')
                ->onDelete('cascade') ;
            $table->string('classification');
            $table->string('config_key');
            $table->string('config_value');
            $table->boolean('config_bool')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('configurations');
    }
};
