<?php

namespace App\Http\Controllers;

use App\Models\Safe;
use App\Models\Transaction;
use App\Models\transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class TransactionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:سحب', ['only' => ['store']]);
    }
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request;
        $validator = Validator::make($request->all(), [
            'money' => 'required',
            'cashWithdrawalDate' => 'required',
            'reason'=>'required',
        ], [
            'money.required' => 'يرجي ادخال الاموال المراد سحبها',
            'reason.required' => 'يرجي ادخال سبب السحب ',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $safeMoney = Safe::get()->first()->money;
        if($request->money>$safeMoney){
            session()->flash('errorMoney');
            return back();
        }
        if($request->money<0){
            session()->flash('lessThanZero');
            return back();
        }

        $mySafe=Safe::get()->first();
        
        $mySafe->update([
            'money'=>$safeMoney-$request->money,
        ]);

        Transaction::create([
            'money' => $request->money,
            'cashWithdrawalDate'=>$request->cashWithdrawalDate,
            'reason'=>$request->reason,
            'note'=>$request->note,
        ]);

        session()->flash('success');
        return redirect('/safe');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\transactions  $transactions
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\transactions  $transactions
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\transactions  $transactions
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\transactions  $transactions
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        //
    }
}
