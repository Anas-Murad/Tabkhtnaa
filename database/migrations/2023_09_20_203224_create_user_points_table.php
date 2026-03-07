<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_points', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\User::class )->index()
                ->constrained('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

                $table->foreignIdFor(\App\Models\Order::class )->index()
                    ->constrained('orders')
                    ->onUpdate('cascade')
                    ->onDelete('cascade');

            $table->foreignIdFor(\App\Models\UserPointTransfers::class )->index()
                ->constrained('user_point_transfers')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->integer('points');

            $table->enum('status' ,['new' , 'used']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_points');
    }
};
