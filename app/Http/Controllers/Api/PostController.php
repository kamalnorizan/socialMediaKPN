<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('user', 'comments.user')->latest()->get()->map(function ($post) {
            return [
                'id' => $post->id,
                'uuid' => $post->uuid,
                'content' => $post->content,
                'created_at' => $post->created_at,
                'user' => [
                    'id' => $post->user->id,
                    'name' => $post->user->name,
                    'email' => $post->user->email,
                ],
                'comments' => $post->comments->map(function ($comment) {
                    return [
                        'id' => $comment->id,
                        'content' => $comment->content,
                        'created_at' => $comment->created_at,
                        'user' => [
                            'id' => $comment->user->id,
                            'name' => $comment->user->name,
                            'email' => $comment->user->email,
                        ],
                    ];
                }),
            ];
        });

        return response()->json($posts);
    }

    public function myposts(Request $request)
    {
        $user = Auth::user();

        $posts = Post::with('user', 'comments.user')
            ->where('user_id', $user->id)
            ->latest()
            ->get()
            ->map(function ($post) {
                return [
                    'id' => $post->id,
                    'uuid' => $post->uuid,
                    'content' => $post->content,
                    'created_at' => $post->created_at,
                    'user' => [
                        'id' => $post->user->id,
                        'name' => $post->user->name,
                        'email' => $post->user->email,
                    ],
                    'comments' => $post->comments->map(function ($comment) {
                        return [
                            'id' => $comment->id,
                            'content' => $comment->content,
                            'created_at' => $comment->created_at,
                            'user' => [
                                'id' => $comment->user->id,
                                'name' => $comment->user->name,
                                'email' => $comment->user->email,
                            ],
                        ];
                    }),
                ];
            });

        return response()->json($posts);
    }

    public function store(Request $request) {
        $request->validate([
            'content' => 'required|string|max:255',
        ]);

        $post = new Post();
        $post->content = $request->input('content');
        $post->uuid = (string) \Illuminate\Support\Str::uuid(); // Generate a UUID for the post
        $post->user_id = auth()->id(); // Assuming you have authentication set up
        $post->save();

        return response()->json(['message' => 'Post created successfully', 'post' => $post], 201);
    }

    public function update(Request $request, Post $post) {
        $request->validate([
            'content' => 'required|string|max:255',
        ]);

        // Check if the authenticated user is the owner of the post
        if ($post->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $post->content = $request->input('content');
        $post->save();

        return response()->json(['message' => 'Post updated successfully', 'post' => $post]);
    }

    public function destroy(Post $post) {
        // Check if the authenticated user is the owner of the post
        if ($post->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);
    }

}
