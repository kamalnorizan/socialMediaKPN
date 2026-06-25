<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $posts = Post::with('user')->get(); // Eager load the user relationship
        // $posts = Post::get(); // Lazy load the user relationship


        // foreach ($posts as $post) {
        //     echo $post->user->name .'<br>';
        // }
        // return response()->json($posts);

        // if($filter == null){
        //     abort(404);
        // }
        // dd($filter);
        // $posts = DB::select('SELECT * FROM posts where user_id = ? ORDER BY created_at DESC', [$filter]);

        // dd($posts);

        $posts = Post::with('user', 'comments.user')->latest()->get()->map(function ($post) {
            return [
                'id' => $post->id,
                'uuid' => $post->uuid,
                'content' => $post->content,
                'created_at' => $post->created_at,
                'user' => [
                    'id' => $post->user->id,
                    'name' => $post->user->name,
                ],
                'comments' => $post->comments->map(function ($comment) {
                    return [
                        'id' => $comment->id,
                        'content' => $comment->content,
                        'created_at' => $comment->created_at,
                        'user' => [
                            'id' => $comment->user->id,
                            'name' => $comment->user->name,
                        ],
                    ];
                }),
            ];
        });
        $name = 'John Doe';
        return Inertia::render('Posts/Index', [
            'posts' => $posts,
            'name' => $name,
        ]);
        // return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $post = new Post();
        $post->content = $request->input('content');
        $post->uuid = (string) Str::uuid(); // Generate a UUID for the post
        $post->user_id = auth()->id(); // Assuming you have authentication set up
        $post->save();

        return redirect()->route('posts.index')->with('success', 'Post created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        dd($post);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        // $this->authorize('update', $post);

        return Inertia::render('Posts/Edit', [
            'post' => [
                'id' => $post->id,
                'content' => $post->content,
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $post->content = $request->input('content');
        $post->save();

        return redirect()->route('posts.index')->with('success', 'Post updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        // $this->authorize('delete', $post);

        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
    }
}
