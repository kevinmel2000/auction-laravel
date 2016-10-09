<?php

namespace App\Http\Controllers;

use App\Models\Name;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\NamesRequest;
use View;

class NamesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('names.index');
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
            'html' => preg_replace("/\\r\\n|\\n/", "", View::make('names.add')->render())
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param NamesRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(NamesRequest $request)
    {
        $name = Name::create(['name' => $request->input('name')]);

        return response()->json([
            'status' =>  $name ? 'success' : 'error',
            'data' => $name
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
                    'names.edit',
                    [
                        'name' => Name::where('id', $id)->first()
                    ]
                )->render()
            )
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param NamesRequest|Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(NamesRequest $request, $id)
    {
        $name = Name::where('id', $id)->first();

        return response()->json([
            'status' =>  $name->update(['name' => $request->input('name')]) ? 'success' : 'error',
            'data' => $name
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
        $name = Name::where('id', $id)->first();

        return response()->json([
            'status' =>  $name->delete() ? 'success' : 'error',
            'id' => $name->id
        ]);
    }

    /**
     * @return mixed
     */
    public function table()
    {
        return response()->json(Name::all());
    }
}
