<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('banner_banners', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->integer('category_id')->references('id')->on('advert_categories')->onDelete('cascade');
            $table->foreignId('region_id')->constrained('locations')->onDelete('cascade');

            $table->string('name');
            $table->integer('views')->nullable();
            $table->integer('limit');
            $table->integer('clicks')->nullable();
            $table->integer('cost')->nullable();
            $table->string('url');
            $table->string('format');
            $table->string('file');
            $table->string('status', 16);
            $table->text('reject_reason')->nullable();

            $table->timestamps();
            $table->timestamp('published_at')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('banner_banners');
    }
};
