<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ImageService;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Repositories\Factories\PostsRepository;

class PostController extends Controller
{
    /**
     * Var $postRepository
     */
    private $postRepository;

    /**
     * Construct PostController
     *
     * @param PostsRepository $postRepository Post repository
     */
    public function __construct(PostsRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request Request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->search ?? '';
        $posts = $this->postRepository->search($search);
        return view('post.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('post.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreatePostRequest $request Request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePostRequest $request)
    {
        $data = $request->all();
        $data['image'] = app(ImageService::class)->upload($request);
        $data['user_id'] = auth()->user()->id;
        $this->postRepository->create($data);
        return redirect()->route('posts.index');
    }

    /**
     * Show the form for update post.
     *
     * @param int $postId Post id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(int $postId)
    {
        $post = $this->postRepository->getPostById($postId);
        return view('post.edit', compact('post'));
    }

    /**
     * Update post.
     *
     * @param CreatePostRequest $request request
     * @param int               $postId  Post id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, int $postId)
    {
        $post = $this->postRepository->getPostById($postId);
        $data = $request->only(['title', 'description']);
        $data['image'] = app(ImageService::class)->upload($request, $post);
        $this->postRepository->update($data, $post);
        return redirect()->route('posts.index');
    }
    
    /**
     * Destroy post.
     *
     * @param int $postId Post id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $postId)
    {
        $post = $this->postRepository->getPostById($postId);
        $this->postRepository->delete($post);
        return redirect()->back();
    }
}
