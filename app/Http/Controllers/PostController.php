<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function index(){
        $posts = Post::all();
        if ($posts->count() <= 0){
            return response()->json([
                'error' => 'No posts found'
            ], 404);
        }else{
            return response()->json([
                'posts' => $posts
            ], 200);

        }
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            // 'user_id' => 'required',
            'title' => 'required|string',
            'image' => 'required|string',
            'body' => 'required|string|max:60',
        ]);

        if($validator->fails()){
            return response()->json([
                'error' => $validator->errors()
            ], 400);
        }
        $post = Post::create([
            // 'user_id' => $request->user->id,
            'title' => $request->title,
            'image' => $request->image,
            'body' => $request->body
        ]);
        
        if($post){
            return response()->json([
                'message' => "Post Successfully Created",
                'posts' => $post,
            ], 200);
        }else{
            return response()->json([
                'error' => 'Failed to create post'
            ], 500);
        }
    }

    public function delete($id){
        $post = Post::find($id);
        if($post){
            $post->delete();
            return response()->json([
                'message' => 'Post Successfully Deleted'
            ], 200);
        }else{
            return response()->json([
                'error' => 'Post not found'
            ], 404);
        }
    }

    public function edit($id, Request $request)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                'error' => 'Post not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string',
            'image' => 'sometimes|string',
            'body' => 'sometimes|string|max:60',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 400);
        }

        $post->title = $request->input('title', $post->title);
        $post->image = $request->input('image', $post->image);
        $post->body = $request->input('body', $post->body);

        $post->save();

        return response()->json([
            'message' => 'Post Successfully Updated',
            'post' => $post,
        ], 200);
    }


}
