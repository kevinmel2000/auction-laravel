<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\CategoryRequest;
use View;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('categories.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return response()->json([
            'status' => 'success',
            'html' => preg_replace("/\\r\\n|\\n/", "", View::make('categories.add')->render())
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CategoryRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryRequest $request)
    {
        $category = Category::create(['name' => $request->input('name')]);

        return response()->json([
            'status' =>  $category ? 'success' : 'error',
            'data' => $category
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return response()->json([
            'status' => 'success',
            'html' => preg_replace(
                "/\\r\\n|\\n/",
                "",
                View::make(
                    'categories.edit',
                    [
                        'category' => Category::where('id', $id)->first()
                    ]
                )->render()
            )
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CategoryRequest|Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, $id)
    {
        $category = Category::where('id', $id)->first();

        return response()->json([
            'status' =>  $category->update(['name' => $request->input('name')]) ? 'success' : 'error',
            'data' => $category
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::where('id', $id)->first();

        return response()->json([
            'status' =>  $category->delete() ? 'success' : 'error',
            'id' => $category->id
        ]);
    }

    /**
     * @return mixed
     */
    public function table()
    {
        return response()->json(Category::all());
    }
}
