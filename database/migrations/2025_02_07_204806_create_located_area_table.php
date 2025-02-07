<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('located_area', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country')->constrained('located_countrys')->onDelete('cascade');
            $table->foreignId('region')->constrained('located_region')->onDelete('cascade');
            $table->string('area', 150);
            $table->string('slug', 150)->unique()->nullable(); // Поле для URL
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('located_area');
    }
};

