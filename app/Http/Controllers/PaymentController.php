<?php

namespace App\Http\Controllers;

use App\Helpers\PaymentServices\Robokassa;
use App\Models\Transaction;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Validator;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     *
     * Pay via Robokassa service
     *
     * @param Request $request
     */
    public function payViaRobokassa(Request $request)
    {
        $response = [];

        $v = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'betpack' => ['required', 'regex:/^(15|25|50|100|250|500|1000|2500)$/']
        ]);

        if ($v->fails())
        {
            $response['status'] = 'fail';
            $response['errors'] = $v->errors();
        } else {

            $robokassa = new Robokassa(['package' => $request->input('betpack')]);

            $transaction = Transaction::create([
                'user_id' => Auth::user()->id,
                'total_amount' => $robokassa->invoiceSum,
                'type' => 'bet',
                'status' => 'pending',
                'note' => $robokassa->invoiceDesc
            ]);

            $robokassa->invoiceNumber = $transaction->id;


            $response['status'] = 'success';
            $response['url'] = $robokassa->getLink();
        }

        return response()->json($response);
    }

    /**
     *
     * Result url from Robokassa service
     *
     * @param Request $request
     */
    public function robokassaResult(Request $request){
        $transaction = Transaction::find($request->input($request->input('invId')));

        if($transaction){
            $transaction->name = 'approved';
            $transaction->save();
        }

        
    }

    /**
     *
     * Success url from Robokassa service
     *
     * @param Request $request
     */
    public function robokassaSuccess(Request $request){
        return redirect('/');
    }

    /**
     *
     * Fail url from Robokassa service
     *
     * @param Request $request
     */
    public function robokassaFail(Request $request){
        return redirect('packages');
    }
}
