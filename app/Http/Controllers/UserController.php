<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    function createPost(Request $request)
    {
        try {
            $post = Post::create([
                'name' => $request->name,
                'user_id' => Auth::id()
            ]);
            return response()->json([
                'status' => 'success',
                'data' => $post
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th->getMessage(),
            ]);
        }
       
    }

    function createComment(Request $request)
    {
        try {
            $comment = Comment::create([
                'comments' => $request->comments,
                'user_id' => Auth::id(),
                'post_id' => $request->post_id
            ]);
            return response()->json([
                'status' => 'success',
                'data' => $comment
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th->getMessage(),
            ]);
        }
      
    }
    function likePostComment(Request $request)
    {
        try {
            $type = $request->type;
            $id = $request->id;
            $user = Auth::user();
            if ($type == 'post') {
                $post = Post::find($id);
                if ($user->id == $post->user_id) {
                    return response()->json([
                        'message' => 'Sorry you are not allowed to like your own post.',
                    ]);
                }
               $is_post_already_liked =  Like::where('likeable_id', $post->id)->where('likeable_type',Post::class)->where('user_id', $user->id)->exists();
                if ($is_post_already_liked) {
                    return response()->json([
                        'message' => 'You have already liked this post.',
                    ], 422);
                }
                $post->likes()->create(['likeable_id' => $post->id, 'likeable_type' => Post::class, 'user_id' => Auth::id()]);
                return response()->json([
                    'status' => 200,
                    'message' => 'Post Liked successfully'
                ]);
            } else {
                $comment = Comment::find($id);
                if ($user->id == $comment->user_id) {
                    return response()->json([
                        'message' => 'Sorry you are not allowed to like your own comment.',
                    ]);
                }
                $is_comment_already_liked = Like::where('likeable_id', $comment->id)->where('likeable_type',Comment::class)->where('user_id', $user->id)->exists();
                if ($is_comment_already_liked) {
                    return response()->json([
                        'message' => 'You have already liked this comment.',
                    ], 422);
                }
                $comment->likes()->create(['likeable_id' => $comment->id, 'likeable_type' => Comment::class, 'user_id' => Auth::id()]);
                return response()->json([
                    'status' => 200,
                    'message' => 'Comment Liked successfully'
                ]);
            }

        } catch (\Throwable $th) {
            return response() ->json([
                'status' =>500,
                'message' => $th->getMessage(),
            ]);
        }
    }
    function getPostsWithLikeComments(Request $request)
    {
        try {
            $postId = $request->id;
            $posts = Post::with(['comments.likes','likes.user'])->findOrFail($postId);
            foreach ($posts->comments as $comment) {
                $comment->total_likes = $comment->likes->count();
            }
            $posts->each(function ($post) {
                $post->total_post_likes = $post->likes->count();
            });
            return response()->json([
                'status' => 200,
                'data' => $posts
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 500,
                'message' => $th->getMessage()
            ]);
        }
    }
    function unlikePostOrComment(Request $request)  {
        try {
            $type = $request->type;
            $id = $request->id;
            $user = Auth::user();
            if ($type == 'post') {
                $post = Post::find($id);
                if ($user->id == $post->user_id) {
                    return response()->json([
                        'message' => 'Sorry you are not allowed to unlike your own post.',
                    ]);
                }
                $post->likes()->delete();
                return response()->json([
                    'status' => 200,
                    'message' => 'Post Unliked Successfully'
                ]);
            } else {
                $comment = Comment::find($id);
                if ($user->id == $comment->user_id) {
                    return response()->json([
                        'message' => 'Sorry you are not allowed to unlike your own comment.',
                    ]);
                }
                $comment->likes()->delete();
                return response()->json([
                    'status' => 200,
                    'message' => 'Comment Unliked successfully'
                ]);
            }

        } catch (\Throwable $th) {
            return response() ->json([
                'status' =>500,
                'message' => $th->getMessage(),
            ]);
        }
    }
    
}