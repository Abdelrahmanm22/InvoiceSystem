<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class ProductReportController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:تقرير المنتجات', ['only' => ['index']]);
    }
    public function index()
    {
        $sections = Section::all();
        return view('reports.products_reports', compact('sections'));
    }


    public function Search_product(Request $request)
    {
        // return $request->product;
        $validator = Validator::make($request->all(), [
            'Section'=>'required',
        ], [
            'Section.required'=>'يجب ادخال قسم المنتج واسمه',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        // في حالة البحث بدون التاريخ
        $product=Product::where('Product_name',$request->product)->first();
        $mount = $product->quantity;
        if ($request->Section && $request->product && $request->start_at == '' && $request->end_at == '') {

            $productSale = OrderDetail::where('product_id', $product->id)->get();
            $sections = Section::all();
            return view('reports.products_reports', compact('sections','mount'))->withDetails($productSale);
        }


        // في حالة البحث بتاريخ

        else {

            $start_at = $request->start_at . ' 00:00:00';
            $end_at = $request->end_at . ' 23:59:59';

            $productSale = OrderDetail::whereBetween('created_at',[$start_at,$end_at])->where('product_id', $product->id)->get();

            $sections = Section::all();
            return view('reports.products_reports', compact('sections','mount'))->withDetails($productSale);
        }
    }
}
