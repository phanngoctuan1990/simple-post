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
            ->where(
                function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%");
                }
            )
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
        return Post::whereUserId(auth()->user()->id)->findOrFail($postId, $select);
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

    /**
     * delete post
     *
     * @param Post $post Post
     *
     * @return void
     */
    public function delete(Post $post): void
    {
        $post->delete();
    }
}
