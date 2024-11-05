<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $posts = Post::get();
        return response()->json([
            'message'=>'List of post',
            'posts'=> $posts
        ],200);
    }

    public function createPost(Request $request)
    {
        // print_r($request);die();
        $post = new Post;
        $post->title = $request->title;
        $post->content = $request->content;
        $post->save();
        return response()->json([
            'message'=>'post created',
            'post'=> $post
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return response()->json([
            'message'=>'single post',
            'post'=> $post
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, Post $post)
    // {
    //     //
    //     dd($post->toArray());
    //     $post->title = $request->title ?? $post->title;
    //     $post->content = $request->content ?? $post->content;
    //     $post->save();
    //     return response()->json([
    //         'message'=>'post updated',
    //         'post'=> $post
    //     ],200);
    // }

    public function update(Request $request, $id)
    {
        // Validate incoming request data
        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
        ]);

        // Find the post by ID or fail
        $post = Post::findOrFail($id);

        // Update fields if present in the request
        if ($request->has('title')) {
            $post->title = $request->title;
        }

        if ($request->has('content')) {
            $post->content = $request->content;
        }

        // Save the updated post
        $post->save();

        // Return a JSON response
        return response()->json([
            'message' => 'Post updated successfully',
            'post' => $post,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
        return response()->json([
            'message' => 'Post Deleted',
            'post' => $post->delete(),
        ], 200);
    }
}
