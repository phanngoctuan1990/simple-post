<?php

namespace App\Http\Controllers\Api\V1;

use App\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePostRequest;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request Request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $userId = auth()->user()->id;
        $page = $request->page ?? null;

        return Post::with('user')
            ->whereUserId($userId)
            ->when($search, function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            })
            ->orderBy($request->sortBy, $request->descending)
            ->paginate($request->rowsPerPage, ['*'], 'page', $page);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request Request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePostRequest $request)
    {
        $data = $request->only(['title', 'description']);
        $data['user_id'] = auth()->user()->id;
        $post = Post::create($data)->load('user');
        return $post;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id Id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $id = $id;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request Request
     * @param int                      $id      Id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $id = $id;
        $request = $request;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id Id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = $id;
    }
}
