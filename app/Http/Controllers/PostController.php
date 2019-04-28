<?php

namespace App\Http\Controllers;

use Storage;
use Illuminate\Http\Request;
use App\Http\Requests\CreatePostRequest;
use App\Repositories\Factories\PostsRepository;

class PostController extends Controller
{
    /**
     * var $postRepository
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePostRequest $request)
    {
        $data = $request->all();
        if ($request->hasFile('image')) {
            $name = time() . '.' . $request->image->getClientOriginalExtension();
            $image = $request->image;
            $path = Storage::disk('s3')->put('post-image/' . $name, file_get_contents($image), 'public');
            $data['image'] = $name;
        }
        $data['user_id'] = auth()->user()->id;
        $post = $this->postRepository->create($data);
        return redirect()->route('post.index');
    }
}
