<?php

namespace App\Providers;

use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Illuminate\Support\ServiceProvider;
use App\Repositories\EloquentPostsRepository;
use App\Repositories\Factories\PostsRepository;
use App\Repositories\ElasticsearchPostsRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(
            PostsRepository::class,
            function ($app) {
                // This is useful in case we want to turn-off our
                // search cluster or when deploying the search
                // to a live, running application at first.
                if (config('services.search.enabled')) {
                    return new ElasticsearchPostsRepository(
                        $app->make(Client::class)
                    );
                }
                return new EloquentPostsRepository;
            }
        );

        $this->bindSearchClient();
    }

    /**
     * Bin search client
     *
     * @return void
     */
    private function bindSearchClient()
    {
        $this->app->bind(
            Client::class,
            function () {
                return ClientBuilder::create()
                    ->setHosts(config('services.search.hosts'))
                    ->setRetries(config('services.search.retries'))
                    // ->setLogger(ClientBuilder::defaultLogger(storage_path('logs/elastic.log')))
                    ->build();
            }
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
