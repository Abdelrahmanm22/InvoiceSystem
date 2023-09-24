<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class SafeController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:الخزنه', ['only' => ['index']]);
    }
    public function index(){
        $transactions = Transaction::orderBy('id', 'desc')->get();
        return view('safe.safe',compact('transactions'));
    }
}
