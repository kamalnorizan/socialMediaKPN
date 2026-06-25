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

}
