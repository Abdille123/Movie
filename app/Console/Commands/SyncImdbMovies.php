<?php

namespace App\Console\Commands;

use App\Services\MovieSyncService;
use Illuminate\Console\Command;

/**
 * This command pulls movie data from OMDb into the local database.
 */
class SyncImdbMovies extends Command
{
    protected $signature = 'movies:sync-imdb {query? : Optional search term to import from OMDb}';

    protected $description = 'Sync featured movies or search results from the OMDb API using IMDb-linked data.';

    /**
     * Run the command with either a search term or the featured list.
     */
    public function handle(MovieSyncService $movieSyncService): int
    {
        $query = trim((string) $this->argument('query'));

        if ($query !== '') {
            $movieSyncService->syncSearchResults($query);
            $this->info("Imported IMDb-linked results for '{$query}'.");

            return self::SUCCESS;
        }

        $movieSyncService->syncFeaturedMovies(force: true);
        $this->info('Featured IMDb-linked movies synced.');

        return self::SUCCESS;
    }
}
