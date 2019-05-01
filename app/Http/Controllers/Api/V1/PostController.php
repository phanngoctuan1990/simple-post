<?php

namespace App\Http\Controllers\Api\V1;

use App\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostCollection;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request Request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $columns = ['id', 'title', 'description'];
        if ($request->client) {
            return Post::get($columns);
        }
        $length = $request->length;
        $column = $request->column;
        $dir = $request->dir;
        $search = $request->search;

        $posts = Post::select($columns)->when($search, function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
        })->orderBy($columns[$column], $dir)
            ->paginate($length);
        $posts->draw = $request->draw;
        return new PostCollection($posts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request Request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $request;
    }

    /**
     * Display the specified resource.
     *
     * @param int $id id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $id;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request Request
     * @param int     $id      id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request = $id;
        return $request;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $id;
    }
}
