<?php
namespace App\Repositories\Factories;

use App\Post;
use Illuminate\Database\Eloquent\Collection;

interface PostsRepository
{
    /**
     * Search post by user id
     * 
     * @param string $query Query need to search
     * 
     * @return Collection
     */
    public function search(String $query = ''): Collection;
    
    /**
     * Create post
     * 
     * @param array $params Post params
     * 
     * @return Post
     */
    public function create(array $params): Post;
}