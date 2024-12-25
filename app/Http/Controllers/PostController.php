<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:list-post', only: ['index']),
            new Middleware('permission:create-post', only: ['store']),
            new Middleware('permission:show-post', only: ['show']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $paginate = $request->pagination;
        $order_by = $request->order_by;
        $order = $request->order;
        $soft_deleted = $request->soft_deleted == "true" ? true : false;
        $query = Post::query();
        if ($soft_deleted) {
            $query = $query->withTrashed();
        }
        return $query->orderBy($order_by, $order)->paginate($paginate);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:3|max:100',
            'data' => 'required',
        ]);
        if ($validator->fails()) {
            return response($validator->errors(), 422);
        }
        $input = $request->only(['title', 'data']);
        $post = Post::create($input);

        return response()->json([
            'message' => 'Posted saved!',
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post) {}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json([
                'message' => 'Post not found!',
            ], 404);
        }
        $post->delete();

        return response()->json([
            'message' => 'Post deleted!',
        ], 200);
    }
}
