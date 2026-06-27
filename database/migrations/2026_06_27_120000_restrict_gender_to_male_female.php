<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('users')->where('gender', 'other')->update(['gender' => 'male']);
        DB::table('admins')->where('gender', 'other')->update(['gender' => null]);

        DB::statement("ALTER TABLE users MODIFY COLUMN gender ENUM('male','female') NOT NULL");
        DB::statement("ALTER TABLE admins MODIFY COLUMN gender ENUM('male','female') NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN gender ENUM('male','female','other') NOT NULL");
        DB::statement("ALTER TABLE admins MODIFY COLUMN gender ENUM('male','female','other') NULL");
    }
};
