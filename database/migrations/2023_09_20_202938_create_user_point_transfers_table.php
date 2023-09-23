<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_point_transfers', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(\App\Models\User::class )->index()
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('cascade') ;

            $table->string('points');
            $table->string('offered_type');
            $table->string('offered_id');
            $table->date('validity_date');
            $table->float('discount')->nullable();
            $table->enum('status' ,['new' , 'active' ,'used']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_point_transfers');
    }
};
