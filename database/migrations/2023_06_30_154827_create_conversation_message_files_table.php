<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('conversation_message_files', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('conversation_id')->nullable();
            $table->foreign('conversation_id')->references('id')->on('conversations')
                ->onUpdate('cascade')
                ->nullOnDelete() ;

            $table->unsignedBigInteger('message_id')->nullable();
            $table->foreign('message_id')->references('id')->on('conversation_messages')
                ->onUpdate('cascade')
                ->nullOnDelete() ;

            $table->string('path');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversation_message_files');
    }
};
