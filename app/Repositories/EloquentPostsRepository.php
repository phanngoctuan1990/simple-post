<?php
namespace App\Repositories;

use App\Post;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Factories\PostsRepository;

class EloquentPostsRepository implements PostsRepository
{
    /**
     * Search post by user id
     * 
     * @param string $query Query need to search
     * 
     * @return Collection
     */
    public function search(String $query = ''): Collection
    {
        $userId = auth()->user()->id;
        return Post::where('user_id', $userId)
            ->where(function($q) use($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            })
            ->get();
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
}