<?php

namespace App\Console\Commands;

use App\Post;
use Elasticsearch\Client;
use Illuminate\Console\Command;

class ReindexCommand extends Command
{
    private $search;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:reindex';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Indexes all posts to elasticsearch';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Client $search)
    {
        parent::__construct();
        $this->search = $search;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->info('Indexing all posts. Might take a while...');

        foreach(Post::cursor() as $post) {
            $this->search->index([
                'id' => $post->id,
                'body' => $post->toSearchArray(),
                'type' => $post->getSearchType(),
                'index' => $post->getSearchIndex(),
            ]);

            $this->output->write('.');
        }

        $this->info('Done!');
    }
}
