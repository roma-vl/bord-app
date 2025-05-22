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
        Schema::create('advert_dialogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advert_id')->constrained('advert_adverts')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Автор оголошення
            $table->foreignId('client_id')->constrained('users')->onDelete('cascade'); // Клієнт
            $table->unsignedInteger('user_new_messages')->default(0);
            $table->unsignedInteger('client_new_messages')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advert_dialogs');
    }
};
