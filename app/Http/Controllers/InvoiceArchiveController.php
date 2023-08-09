<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceArchiveController extends Controller
{
    //
    public function index()
    {
        $invoices = Invoice::onlyTrashed()->get();
        return view('invoices.archive', compact('invoices'));
    }

    public function restore(Request $request)
    {
        $id = $request->invoice_id;
        $flight = Invoice::withTrashed()->where('id', $id)->restore();
        session()->flash('restore_invoice');
        return redirect('/invoices');
    }

    public function delete(Request $request){
        $invoices = Invoice::withTrashed()->where('id',$request->invoice_id)->first();
         $invoices->forceDelete();
         session()->flash('delete_invoice');
         return redirect('/Archive');
    }
}
