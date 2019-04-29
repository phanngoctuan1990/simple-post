<?php
namespace App\Repositories\Factories;

use App\Post;
use Illuminate\Database\Eloquent\Collection;

interface PostsRepository
{
    /**
     * Search post by search
     * 
     * @param string $query Query need to search
     * 
     * @return Collection
     */
    public function search(String $query = ''): Collection;
    
    /**
     * Get post by id
     * 
     * @param int   $PostId Post id
     * @param array $select Select column
     * 
     * @return Post
     */
    public function getPostById(int $PostId, array $select = ['*']): Post;
    
    /**
     * Create post
     * 
     * @param array $params Post params
     * 
     * @return Post
     */
    public function create(array $params): Post;
    
    /**
     * Update post
     * 
     * @param array $params Post params
     * @param Post  $post   Post
     * 
     * @return void
     */
    public function update(array $params, Post $post): void;
}