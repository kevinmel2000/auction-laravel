<?php

namespace App\Http\Controllers;

use App\Models\Template;
use App\Models\TemplatePhoto;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\TemplateRequest;
use Storage;
use View;

class TemplatesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('templates.index');
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
            'html' => preg_replace("/\\r\\n|\\n/", "", View::make('templates.add')->render())
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TemplateRequest|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(TemplateRequest $request)
    {
        $template = Template::create([
            'name' => $request->input('name'),
            'description' => $request->input('description')
        ]);

        $result = false;

        if ($template) {
            foreach ($request->input('photos') as $photo) {
                $name = md5(time() . uniqid()) . '.png';

                if (Storage::put(
                    'templates/photos/' . $name,
                    base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $photo)),
                    'public'
                )) {
                    $result = TemplatePhoto::create([
                        'template_id' => $template->id,
                        'name' => $name
                    ]);
                }
            }
        }
        
        return response()->json([
            'status' => $result && $template ? 'success' : 'error',
            'data' => $template
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
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $template = Template::where('id', $id)->first();

        return response()->json([
            'status' => 'success',
            'html' => preg_replace(
                "/\\r\\n|\\n/",
                "",
                View::make(
                    'templates.edit',
                    [
                        'template' => $template
                    ]
                )->render()
            )
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $template = Template::where('id', $id)->first();
        $template->update([
            'name' => $request->input('name'),
            'description' => $request->input('description')
        ]);

        $result = false;

        if ($request->has('deleted')) {
            $photos = TemplatePhoto::whereIn('id', $request->input('deleted'))->get();

            foreach ($photos as $photo) {
                if(Storage::exists('templates/photos/' . $photo->name)) {
                    $result = Storage::delete('templates/photos/' . $photo->name) && $photo->delete();
                }
            }
        }

        if ($request->has('photos')) {
            foreach ($request->input('photos') as $photo) {
                $name = md5(time() . uniqid()) . '.png';

                if (Storage::put(
                    'templates/photos/' . $name,
                    base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $photo)),
                    'public'
                )
                ) {
                    $result = TemplatePhoto::create([
                        'template_id' => $template->id,
                        'name' => $name
                    ]);
                }
            }
        }

        return response()->json([
            'status' => $result && $template ? 'success' : 'error',
            'data' => $template
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
        $template = Template::where('id', $id)->first();
        $result = false;

        foreach ($template->photos as $photo) {
            if(Storage::exists('templates/photos/' . $photo->name)) {
                $result = Storage::delete('templates/photos/' . $photo->name) && $photo->delete();
            }
        }


        return response()->json([
            'status' =>  $result && $template->delete() ? 'success' : 'error',
            'id' => $template->id
        ]);
    }

    public function table()
    {
        return response()->json(Template::all());
    }

    public function upload ()
    {
        return Response::json([
            'status' => 'success',
            'html' => preg_replace("/\\r\\n|\\n/", "", View::make('templates.upload')->render())
        ]);
    }
}
