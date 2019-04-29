@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <form method="get">
            <div class="input-group">
                <input name="search" value="{{ old('search') }}" type="text" class="form-control" placeholder="Search for...">
                <span class="input-group-btn">
                    <button class="btn btn-default" type="submit">Go!</button>
                </span>
            </div>
        </form>
        <div class="col-md-8">
            @foreach($posts as $post)
            <div class="card-deck">
                <div class="card">
                    <img class="card-img-top" src="{{ $post['post_image'] }}" alt="{{ $post['post_image'] }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $post['title'] }}</h5>
                        <p class="card-text">{{ $post['description'] }}</p>
                    </div>
                    <div class="card-footer text-center">
                        <small class="text-muted float-left">{{ $post['created_at'] }}</small>
                        <form method="POST" action="{{ route('posts.destroy', $post['id']) }}">
                            @csrf()
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger float-right">Delete post</button>
                        </form>
                        <a href="{{ route('posts.edit', $post['id']) }}" class="btn btn-primary float-right">Edit post</a>
                    </div>
                </div>
            </div>
            <br>
            @endforeach
        </div>
    </div>
</div>
@endsection
