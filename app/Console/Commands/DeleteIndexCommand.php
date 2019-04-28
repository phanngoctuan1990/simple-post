<?php

namespace App\Console\Commands;

use App\Post;
use Elasticsearch\Client;
use Illuminate\Console\Command;

class DeleteIndexCommand extends Command
{
    private $search;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:delete-index';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove indexes all posts';

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
        $this->info('Remove indexing all posts. Might take a while...');

        foreach(Post::cursor() as $post) {
            $this->search->delete([
                'id' => $post->id,
                'type' => $post->getSearchType(),
                'index' => $post->getSearchIndex(),
            ]);

            $this->output->write('.');
        }

        $this->info('Done!');
    }
}
