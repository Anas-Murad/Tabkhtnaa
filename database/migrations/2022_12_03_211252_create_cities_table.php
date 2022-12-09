<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();

            $table->string('name' );
            $table->string('country_code' ,2)->nullable();
            $table->string('fips_code' )->nullable();
            $table->string('iso2' )->nullable();
            $table->string('type' )->nullable();
            $table->unsignedInteger('country_id' )->nullable();
            $table->decimal('latitude' ,15,8)->nullable();
            $table->decimal('longitude' ,15,8)->nullable();
            $table->boolean('flag' )->default(true);
            $table->string('wikiDataId' ,75)->nullable()->comment('Rapid API GeoDB Cities');


            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cities');
    }
};
