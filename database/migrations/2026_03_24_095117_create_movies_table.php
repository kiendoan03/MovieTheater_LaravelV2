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
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('movie_name', 255);
            $table->text('logo');
            $table->text('poster');
            $table->text('thumbnail');
            $table->double('rating', 2, 1);
            $table->text('synopsis');
            $table->string('language', 255);
            $table->integer('length');
            $table->date('release_date');
            $table->date('end_date');
            $table->integer('age_restricted');
            $table->text('trailer');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
