<?php
namespace App\Repositories;

use App\Post;
use Elasticsearch\Client;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Factories\PostsRepository;

class ElasticsearchPostsRepository implements PostsRepository
{
    private $search;

    public function __construct(Client $client)
    {
        $this->search = $client;
    }

    public function search(string $search = ''): Collection
    {
        $posts = $this->searchOnElasticsearch($search);

        return $this->buildCollection($posts);
    }

    public function searchOnElasticsearch(string $search): array
    {
        $post = new Post;
        $userId = auth()->user()->id;
        $query['bool']['must'] = [
            [
                "match_phrase" => ["user_id" =>  $userId]
            ]
        ];
        if ($search) {
            $query['bool']['must'][] = [
                "bool" => [
                    "should" => [
                        [
                            "match_phrase" => ["title" => $search]
                        ],
                        [
                            "match_phrase" => ["description" => $search]
                        ]
                    ]
                ]
            ];
        }
        $posts = $this->search->search([
            'index' => $post->getSearchIndex(),
            'type' => $post->getSearchType(),
            'body' => [
                "query" => $query,
                "from" => 0,
                "size" => 1000
            ],
        ]);
        return $posts;
    }

    private function buildCollection(array $posts): Collection
    {
        /**
         * The data comes in a structure like this:
         *
         * [
         *      'hits' => [
         *          'hits' => [
         *              [ '_source' => 1 ],
         *              [ '_source' => 2 ],
         *          ]
         *      ]
         * ]
         *
         * And we only care about the _source of the documents.
        */
        $hits = array_pluck($posts['hits']['hits'], '_source') ?: [];
        // We have to convert the results array into Eloquent Models.
        return Post::hydrate($hits);
    }

    /**
     * Create a post
     * 
     * @param array $params Post params
     * 
     * @return Post
     */
    public function create(array $params): Post
    {
        return Post::create($params);
    }

    /**
     * Get post by id
     * 
     * @param int   $postId Post id
     * @param array $select Select column
     * 
     * @return Post
     */
    public function getPostById(int $postId, array $select = ['*']): Post
    {
        $post = new Post;
        $userId = auth()->user()->id;
        $post = $this->search->search([
            'index' => $post->getSearchIndex(),
            'type' => $post->getSearchType(),
            'body' => [
                "query" => [
                    "bool" => [
                        "must" => [
                            [
                                "match_phrase" => [
                                    "id" => [
                                        "query" => $postId,
                                    ]
                                ]
                            ],
                            [
                                "match_phrase" => [
                                    "user_id" => [
                                        "query" => $userId,
                                    ]
                                ]
                            ]
                        ]
                    ]
                ],
                "from" => 0,
                "size" => 1,
            ],
        ]);
        return $this->buildCollection($post)->first();
    }

    /**
     * Update post
     * 
     * @param array $params Post params
     * @param Post  $post   Post
     * 
     * @return void
     */
    public function update(array $params, Post $post): void
    {
        $post->update($params);
    }
}