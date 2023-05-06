<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
     /**
     * API of List posts
     *
     *@param  \Illuminate\Http\Request  $request
     *@return $post
     */
    public function list(Request $request)
    {
        $this->validate($request, [
            'page'          => 'nullable|integer',
            'perPage'       => 'nullable|integer',
            'search'        => 'nullable',
            'sort_field'    => 'nullable',
            'sort_order'    => 'nullable|in:asc,desc',
        ]);

        $query = Post::query()->with('category');
        if ($request->search) {
            $query = $query->where('name', 'like', "%$request->search%");
        }

        if ($request->sort_field && $request->sort_order) {
            $query = $query->orderBy($request->sort_field, $request->sort_order);
        }

        /* Pagination */
        $count = $query->count();
        if ($request->page && $request->perPage) {
            $page = $request->page;
            $perPage = $request->perPage;
            $query = $query->skip($perPage * ($page - 1))->take($perPage);
        }

        /* Get records */
        $post = $query->get();
        $data = [
            'count'       => $count,
            'posts'  => $post
        ];
        // dd($data);

       return response()->json([
        'data' =>$data       ]);
       }

    /**
     * API of Create post
     *
     *@param  \Illuminate\Http\Request  $request
     *@return $post
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'category_id' => 'required|exists:categories,id',
            'name'   => 'required',
        ]);
        $post =Post::create($request->only('category_id','name'));
        return response()->json([
            'message' =>'Post created successfully',
            'data'    =>$post       ]);
           }
    

    /**
     * API of get perticuler post details
     *
     * @param  $id
     * @return $post
     */
    public function get($id)
    {
        $post = Post::findOrFail($id);

        return response()->json([
            'message' =>'Post get successfully',
            'data'    =>$post       ]);
           }
    

    /**
     * API of Update post
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'category_id' => 'required|exists:categories,id',
            'name'   => 'required',
        ]);

        $post = Post::findOrFail($id);
        $post->update($request->only('cateory_id','name'));

        return response()->json([
            'message' =>'post updated successfully',
            'data'    =>$post       ]);
    }

    /**
     * API of Delete post
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     */
    public function delete($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return response()->json([
            'message' =>'Post deleted successfully',
                  ]);
    }
}
