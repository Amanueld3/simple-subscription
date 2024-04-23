<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Jobs\SendEmailToSubscribers;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(['data' => Post::paginate(10)]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function get_all()
    {
        return response()->json(['data' => Post::all()]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        DB::beginTransaction();

        try {
            $post = Post::create([
                'title' => $request->title,
                'content' => $request->content,
                'website_id' => $request->website_id,
            ]);
            DB::commit();

            return $post;
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => 'An error occurred'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return $post;
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        DB::beginTransaction();

        try {
            $post->update([
                'title' => $request->title ?? $post->title,
                'content' => $request->content ?? $post->content,
                'website_id' => $request->website_id ?? $post->website_id,
            ]);
            DB::commit();

            SendEmailToSubscribers::dispatch();

            return $post;
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['message' => 'An error occurred'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();

        return response()->json(['message' => 'deleted']);
    }
}
