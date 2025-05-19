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
        Schema::create('advert_adverts', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('category_id')->references('id')->on('advert_categories')->onDelete('cascade');
            $table->foreignId('region_id')->constrained('locations')->onDelete('cascade');

            $table->string('title');
            $table->integer('price');
            $table->text('address');
            $table->text('content');
            $table->string('status', 16);
            $table->text('reject_reason')->nullable();
            $table->boolean('premium')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('advert_advert_values', function (Blueprint $table) {
            $table->integer('advert_id')->references('id')->on('advert_adverts')->onDelete('cascade');
            $table->integer('attribute_id')->references('id')->on('advert_attributes')->onDelete('cascade');
            $table->string('value');
            $table->primary(['advert_id', 'attribute_id']);
        });
        Schema::create('advert_advert_photos', function (Blueprint $table) {
            $table->id();
            $table->integer('advert_id')->references('id')->on('advert_adverts')->onDelete('cascade');
            $table->string('file');
        });
        Schema::create('advert_advert_favorites', function (Blueprint $table) {
            $table->integer('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('advert_id')->references('id')->on('advert_adverts')->onDelete('cascade');
            $table->primary(['user_id', 'advert_id']);
        });
        Schema::create('advert_advert_views', function (Blueprint $table) {
            $table->id();
            $table->integer('advert_id')->references('id')->on('advert_adverts')->onDelete('cascade');
            $table->integer('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('created_at');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advert_adverts');
        Schema::dropIfExists('advert_advert_value');
        Schema::dropIfExists('advert_advert_photos');
        Schema::dropIfExists('advert_advert_favorites');
        Schema::dropIfExists('advert_advert_views');
    }
};
