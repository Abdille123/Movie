<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create the main movies table.
     */
    public function up(): void
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('tagline');
            $table->text('synopsis');
            $table->string('genre');
            $table->unsignedSmallInteger('release_year');
            $table->unsignedSmallInteger('runtime_minutes');
            $table->string('age_rating', 12);
            $table->unsignedTinyInteger('critic_score');
            $table->unsignedTinyInteger('audience_score');
            $table->string('tone', 24)->default('ember');
            $table->boolean('featured')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Remove the movies table.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
