<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_live_locations', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            $table->string('latitude');
            $table->string('longitude');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_live_locations');
    }
};
