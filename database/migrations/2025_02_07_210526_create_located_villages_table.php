<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('located_village', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country')->constrained('located_countrys')->onDelete('cascade');
            $table->foreignId('region')->constrained('located_region')->onDelete('cascade');
            $table->foreignId('area')->constrained('located_area')->onDelete('cascade');
            $table->string('village', 150);
            $table->string('slug', 150)->unique()->nullable();
            $table->timestamps();

            $table->unique(['area', 'village']);
        });

        $sqlFile = database_path('dumps/users.sql');
        if (file_exists($sqlFile)) {
            DB::unprepared(file_get_contents($sqlFile));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('located_village');
    }
};
