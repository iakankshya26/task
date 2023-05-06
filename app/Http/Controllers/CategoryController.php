<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    
    /**
     * API of List category
     *
     *@param  \Illuminate\Http\Request  $request
     *@return $category
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

        $query = Category::query();
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
        $category = $query->get();
        $data = [
            'count'       => $count,
            'categories'  => $category
        ];
        // dd($data);

       return response()->json([
        'data' =>$data       ]);
       }

    /**
     * API of Create category
     *
     *@param  \Illuminate\Http\Request  $request
     *@return $category
     */
    public function create(Request $request)
    {
        $this->validate($request, [
            'name'   => 'required',
        ]);
        $category = Category::create($request->only('name'));
        return response()->json([
            'message' =>'Category created successfully',
            'data'    =>$category       ]);
           }
    

    /**
     * API of get perticuler Category details
     *
     * @param  $id
     * @return $category
     */
    public function get($id)
    {
        $category = Category::findOrFail($id);

        return response()->json([
            'message' =>'Category get successfully',
            'data'    =>$category       ]);
           }
    

    /**
     * API of Update category
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|unique:categories,name',
        ]);

        $category = Category::findOrFail($id);
        $category->update($request->only('name', 'slug'));

        return response()->json([
            'message' =>'Category updated successfully',
            'data'    =>$category       ]);
    }

    /**
     * API of Delete Category
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     */
    public function delete($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json([
            'message' =>'Category deleted successfully',
                  ]);
    }
}