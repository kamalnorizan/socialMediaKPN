<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    /** @use HasFactory<\Database\Factories\CommentFactory> */
    use HasFactory;

    public $timestamps = true;

    // public const CREATED_AT = 'created';
    // public const UPDATED_AT = 'updated';

    public $incrementing = true;

    protected $guarded = ['id'];
    // $request = new Request();
    // $request->validate([
    //     'post_id' => 'required|exists:posts,id',
    //     'user_id' => 'required|exists:users,id',
    //     'content' => 'required|string|max:255',
    //     'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    // ]);

    // Comment::create($request->only(['post_id', 'user_id', 'content']));
    // // insert into comments (post_id, user_id, content) values (1, 1, 'This is a comment.');

    // protected $fillable = ['post_id', 'user_id', 'content'];

    // $request = new Request();
    // $request->validate([
    //     'post_id' => 'required|exists:posts,id',
    //     'user_id' => 'required|exists:users,id',
    //     'content' => 'required|string|max:255',
    //     'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    // ]);

    // Comment::create($request->all());
    // insert into comments (post_id, user_id, content) values (1, 1, 'This is a comment.');


}
