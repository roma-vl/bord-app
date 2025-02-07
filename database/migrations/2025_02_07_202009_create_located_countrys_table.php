<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('located_countrys', function (Blueprint $table) {
            $table->id();
            $table->string('country', 60)->unique();
            $table->string('slug', 60)->unique(); // Поле для URL
            $table->timestamps();
        });

        // Вставка початкових даних
        \DB::table('located_countrys')->insert([
            ['id' => 1, 'country' => 'Україна', 'slug' => 'ukraine']
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('located_countrys');
    }
};
