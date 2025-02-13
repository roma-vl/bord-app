<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('phone_verified')->default(false)->after('phone');
            $table->string('phone_verify_token')->nullable()->after('phone_verified');
            $table->timestamp('phone_verify_token_expire')->nullable()->after('phone_verify_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone');
            $table->dropColumn('phone_verified');
            $table->dropColumn('phone_verify_token');
            $table->dropColumn('phone_verify_token_expire');
        });
    }
};
