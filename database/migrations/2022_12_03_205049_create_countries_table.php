<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();

            $table->string('name' );
            $table->string('iso3' ,3)->nullable();
            $table->string('numeric_code' ,3)->nullable();
            $table->string('iso2' ,2)->nullable();
            $table->string('phonecode' ,75)->nullable();
            $table->string('capital' ,75)->nullable();
            $table->string('currency' ,75)->nullable();
            $table->string('currency_name' ,75)->nullable();
            $table->string('currency_symbol' ,75)->nullable();
            $table->string('tld' ,75)->nullable();
            $table->string('native' ,75)->nullable();
            $table->string('region' ,75)->nullable();
            $table->string('subregion' ,75)->nullable();
            $table->text('timezones' ,200)->nullable();
            $table->text('translations' )->nullable();
            $table->decimal('latitude' ,15,8)->nullable();
            $table->decimal('longitude' ,15,8)->nullable();
            $table->string('emoji' ,75)->nullable();
            $table->string('emojiU' ,75)->nullable();
            $table->boolean('flag' )->default(true);
            $table->string('wikiDataId' ,75)->nullable()->comment('Rapid API GeoDB Cities');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('countries');
    }
};
