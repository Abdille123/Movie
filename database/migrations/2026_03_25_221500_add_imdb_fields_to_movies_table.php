<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add extra fields used for OMDb and IMDb-linked movie data.
     */
    public function up(): void
    {
        Schema::table('movies', function (Blueprint $table): void {
            $table->string('imdb_id')->nullable()->unique()->after('id');
            $table->string('director')->nullable()->after('genre');
            $table->string('poster_url')->nullable()->after('tone');
            $table->timestamp('last_synced_at')->nullable()->after('poster_url');
        });
    }

    /**
     * Remove the OMDb and IMDb-linked fields again.
     */
    public function down(): void
    {
        Schema::table('movies', function (Blueprint $table): void {
            $table->dropUnique(['imdb_id']);
            $table->dropColumn([
                'imdb_id',
                'director',
                'poster_url',
                'last_synced_at',
            ]);
        });
    }
};
