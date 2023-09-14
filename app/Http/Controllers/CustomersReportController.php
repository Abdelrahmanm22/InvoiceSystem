<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Section;
use Illuminate\Http\Request;

class CustomersReportController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:تقرير العملاء', ['only' => ['index']]);
    }
    public function index()
    {

        $sections = Section::all();
        return view('reports.customers_reports', compact('sections'));
    }


    public function Search_customers(Request $request)
    {


        // في حالة البحث بدون التاريخ

        if ($request->Section && $request->product && $request->start_at == '' && $request->end_at == '') {


            $invoices = Invoice::select('*')->where('section_id', '=', $request->Section)->where('product', '=', $request->product)->get();
            $sections = Section::all();
            return view('reports.customers_reports', compact('sections'))->withDetails($invoices);
        }


        // في حالة البحث بتاريخ

        else {

            $start_at = date($request->start_at);
            $end_at = date($request->end_at);

            $invoices = Invoice::whereBetween('invoice_Date', [$start_at, $end_at])->where('section_id', '=', $request->Section)->where('product', '=', $request->product)->get();
            $sections = Section::all();
            return view('reports.customers_reports', compact('sections'))->withDetails($invoices);
        }
    }
}
