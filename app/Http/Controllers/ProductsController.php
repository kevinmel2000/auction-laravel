<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Models\Category;
use App\Models\Name;
use App\Models\Product;
use App\Models\Template;
use App\Models\Mongo\Product as MongoProduct;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\ProductRequest;
use View;
use Carbon\Carbon;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('products.index');
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
            'html' => preg_replace(
                "/\\r\\n|\\n/",
                "",
                View::make(
                    'products.add',
                    [
                        'categories' => Category::all()->pluck('name', 'id'),
                        'templates' => Template::all()->pluck('name', 'id'),
                        'names' => Name::all()->pluck('name', 'id')
                    ]
                )->render()
            )
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ProductRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        $startDate = Carbon::createFromFormat('d/m/Y H:i', $request->input('start_date'))->timestamp + ($request->input('offset') * 60);

        $mongo = MongoProduct::create([
            'bot_count' => (int) $request->input('bot_count'),
            'bot_names' => Name::whereIn('id', $request->input('bot_names'))->pluck('name')->toJson(),
            'price' => $request->input('price'),
            'coefficient' => (int) $request->input('coefficient'),
            'date' => $startDate,
            'status' => 1,
        ]);

        if ($mongo) {
            $product = Product::create([
                'mongo_id' => $mongo->_id,
                'template_id' => $request->input('template_id'),
                'category_id' => $request->input('category_id'),
                'bot_count' => $request->input('bot_count'),
                'bot_names' => json_encode($request->input('bot_names')),
                'price' => $request->input('price'),
                'start_date' => $startDate,
                'type' => $request->input('type'),
                'coefficient' => $request->input('coefficient'),
                'source' => $request->input('source'),
                'status' => 1
            ]);
        }

        return response()->json([
            'status' =>  $mongo && $product ? 'success' : 'error',
            'data' => Product::where('id', $product->id)->with('template', 'category')->first()
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
        $product = Product::where('id', $id)->first();

        return response()->json([
            'status' => 'success',
            'html' => preg_replace(
                "/\\r\\n|\\n/",
                "",
                View::make(
                    'products.edit',
                    [
                        'product' => $product,
                        'categories' => Category::all()->pluck('name', 'id'),
                        'templates' => Template::all()->pluck('name', 'id'),
                        'names' => Name::all()->pluck('name', 'id')
                    ]
                )->render()
            )
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ProductRequest|Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id)
    {
        $startDate = Carbon::createFromFormat('d/m/Y H:i', $request->input('start_date'))->timestamp + ($request->input('offset') * 60);
        $product = Product::where('id', $id)->first();

        $result = $product->update([
            'template_id' => $request->input('template_id'),
            'category_id' => $request->input('category_id'),
            'bot_count' => $request->input('bot_count'),
            'bot_names' => json_encode($request->input('bot_names')),
            'price' => $request->input('price'),
            'start_date' => $startDate,
            'type' => $request->input('type'),
            'coefficient' => $request->input('coefficient'),
            'source' => $request->input('source'),
            'status' => 1
        ]) && MongoProduct::find($product->mongo_id)->update([
            'bot_count' => (int) $request->input('bot_count'),
            'bot_names' => Name::whereIn('id', $request->input('bot_names'))->pluck('name')->toJson(),
            'price' => $request->input('price'),
            'coefficient' => (int) $request->input('coefficient'),
            'date' => $startDate,
            'status' => 1
        ]);

        return response()->json([
            'status' => $result ? 'success' : 'error',
            'data' => $product->with('template', 'category')->first()
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
        $product = Product::where('id', $id)->first();

        return response()->json([
            'status' =>  MongoProduct::destroy($product->mongo_id) && $product->delete() ? 'success' : 'error',
            'id' => $product->id
        ]);
    }

    /**
     * @return mixed
     */
    public function table()
    {
        return response()->json(Product::with('template', 'category')->get());
    }
    
    public function page($id)
    {
        return view('products.page', ['product' => Product::where('id', $id)->first()]);
    }
    
    public function information($id)
    {
        return view('products.info', ['template' => Template::where('id', $id)->first()]);
    }

    public function updateStatus(Request $request)
    {
        Product::where('mongo_id', $request->input('id'))
                ->update(['status' => $request->input('status')]);
    }
}