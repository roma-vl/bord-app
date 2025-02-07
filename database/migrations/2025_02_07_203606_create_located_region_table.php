<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('located_region', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country')->default(1)
                ->constrained('located_countrys')->onDelete('cascade');
            $table->string('region', 60);
            $table->string('slug', 60)->unique();
            $table->timestamps();
        });

        // Вставка початкових даних
        \DB::table('located_region')->insert([
            ['id' => 1, 'country' => 1, 'region' => 'Вінницька', 'slug' => 'vinnitska'],
            ['id' => 2, 'country' => 1, 'region' => 'Волинська', 'slug' => 'volynska'],
            ['id' => 3, 'country' => 1, 'region' => 'Дніпропетровська', 'slug' => 'dnipropetrovska'],
            ['id' => 4, 'country' => 1, 'region' => 'Донецька', 'slug' => 'donetska'],
            ['id' => 5, 'country' => 1, 'region' => 'Житомирська', 'slug' => 'zhytomyrska'],
            ['id' => 6, 'country' => 1, 'region' => 'Закарпатська', 'slug' => 'zakarpatska'],
            ['id' => 7, 'country' => 1, 'region' => 'Запорізька', 'slug' => 'zaporizka'],
            ['id' => 8, 'country' => 1, 'region' => 'Івано-Франківська', 'slug' => 'ivano-frankivska'],
            ['id' => 9, 'country' => 1, 'region' => 'Київ', 'slug' => 'kyiv'],
            ['id' => 10, 'country' => 1, 'region' => 'Київська', 'slug' => 'kyivska'],
            ['id' => 11, 'country' => 1, 'region' => 'Кіровоградська', 'slug' => 'kirovohradska'],
            ['id' => 12, 'country' => 1, 'region' => 'Луганська', 'slug' => 'luganska'],
            ['id' => 13, 'country' => 1, 'region' => 'Львівська', 'slug' => 'lvivska'],
            ['id' => 14, 'country' => 1, 'region' => 'Миколаївська', 'slug' => 'mykolaivska'],
            ['id' => 15, 'country' => 1, 'region' => 'Одеська', 'slug' => 'odeska'],
            ['id' => 16, 'country' => 1, 'region' => 'Полтавська', 'slug' => 'poltavska'],
            ['id' => 17, 'country' => 1, 'region' => 'Республіка Крим', 'slug' => 'crimea'],
            ['id' => 18, 'country' => 1, 'region' => 'Рівненська', 'slug' => 'rivnenska'],
            ['id' => 19, 'country' => 1, 'region' => 'Севастополь', 'slug' => 'sevastopol'],
            ['id' => 20, 'country' => 1, 'region' => 'Сумська', 'slug' => 'sumska'],
            ['id' => 21, 'country' => 1, 'region' => 'Тернопільська', 'slug' => 'ternopilska'],
            ['id' => 22, 'country' => 1, 'region' => 'Харківська', 'slug' => 'kharkivska'],
            ['id' => 23, 'country' => 1, 'region' => 'Херсонська', 'slug' => 'khersonska'],
            ['id' => 24, 'country' => 1, 'region' => 'Хмельницька', 'slug' => 'khmelnytska'],
            ['id' => 25, 'country' => 1, 'region' => 'Черкаська', 'slug' => 'cherkaska'],
            ['id' => 26, 'country' => 1, 'region' => 'Чернівецька', 'slug' => 'chernivetska'],
            ['id' => 27, 'country' => 1, 'region' => 'Чернігівська', 'slug' => 'chernihivska']
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('located_region');
    }
};

