<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:المنتجات', ['only' => ['index']]);
        $this->middleware('permission:اضافة منتج', ['only' => ['store']]);
        $this->middleware('permission:تعديل منتج', ['only' => ['update']]);
        $this->middleware('permission:حذف منتج', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $products = Product::with(['section' => function ($q) {
            $q->select('id', 'section_name');
        }])->get();
        $sections = Section::get();
        return view('products.products', compact('products', 'sections'));
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
        //
        // dd($request);
        $validatedData = $request->validate([
            // 'Product_code'=>'required|unique:products|max:99',
            'Product_name' => 'required|unique:products|max:255',
            'section_id' => 'required',
            'price' => 'required|numeric|digits_between:1,10000',
            'mini_price' => 'required|numeric|between:1,100000',
            'Wholesale_Price' => 'required|numeric|between:0,100000',
            'quantity' => 'required|numeric|between:0,100000',
        ], [
            // 'Product_code.required' => 'يرجي ادخال كود المنتج',
            // 'Product_code.unique' => 'كود المنتج مسجل مسبقا',
            'Product_name.required' => 'يرجي ادخال اسم المنتج',
            'Product_name.unique' => 'اسم المنتج مسجل مسبقا',
            'section_id.required' => 'يرجي اختيار اسم القسم',
            'price.required' => 'يرجي ادخال سعر المنتج',
            'price.numeric' => 'يجب ان يكون سعر المنتج مكون من ارقام وليس حروف',
            'price.between' => 'يجب ان يكون سعر المنتج يتراوح بين 1 الي 100000 جنيه',
            'mini_price.required' => 'يرجي ادخال أقل سعر للمنتج',
            'mini_price.numeric' => 'يجب ان يكون اقل سعر للمنتج مكون من ارقام فقط وليس حروف',
            'mini_price.between' => 'يجب ان يكون أقل سعر للمنتج يتراوح بين 1 الي 100000 جنيه',

            'Wholesale_Price.required' => 'يرجي ادخال سعر الجمله',
            'Wholesale_Price.numeric' => 'يجب ان يكون سعر الحمله مكون من ارقام وليس حروف',
            'Wholesale_Price.between' => 'يجب ان يكون سعر الجمله يتراوح بين 0 الي 100000 جنيه',

            'quantity.required' => 'يرجي ادخال كمية المنتج ',
            'quantity.numeric' => 'يجب ان يكون كمية المنتج  مكون من ارقام فقط وليس حروف',
            'quantity.between' => 'يجب ان يكون كمية المنتج يتراوح بين 0 الي 100000 جنيه',
        ]);

        //push data in database
        Product::create([
            // 'Product_code'=>$request->Product_code,
            'Product_name' => $request->Product_name,
            'description' => $request->description,
            'price' => $request->price,
            'mini_price'=>$request->mini_price,
            'Wholesale_Price'=>$request->Wholesale_Price,
            'quantity'=>$request->quantity,
            'section_id' => $request->section_id,
            'user' => (Auth::user()->name),
        ]);
        session()->flash('Add', 'تم اضافة المنتج بنجاح ');
        return redirect('/products');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = $request->id;

        $this->validate($request, [
            // 'Product_code'=>'required|max:99|unique:products,Product_name,'.$id,
            'Product_name' => 'required|max:255|unique:products,Product_name,' . $id, //عشان امنع التقرار في التعديل
            'section_id' => 'required',
            'price' => 'required|numeric|digits_between:1,10000',
            'mini_price' => 'required|numeric|between:1,100000',
            'Wholesale_Price' => 'required|numeric|between:0,100000',
            'quantity' => 'required|numeric|between:0,100000',
        ], [
            
            // 'Product_code.required' => 'يرجي ادخال كود المنتج',
            // 'Product_code.unique' => 'كود المنتج مسجل مسبقا',
            'Product_name.required' => 'يرجي ادخال اسم المنتج',
            'Product_name.unique' => 'اسم المنتج مسجل مسبقا',
            'section_id.required' => 'يرجي اختيار اسم القسم',
            'section_id.required' => 'يرجي اختيار اسم القسم',
            'price.required' => 'يرجي ادخال سعر المنتج',
            'price.numeric' => 'يجب ان يكون سعر المنتج مكون من ارقام فقط وليس حروف',
            'price.between' => 'يجب ان يكون سعر المنتج يتراوح بين 1 الي 100000 جنيه',
            'mini_price.required' => 'يرجي ادخال أقل سعر للمنتج',
            'mini_price.numeric' => 'يجب ان يكون اقل سعر للمنتج مكون من ارقام فقط وليس حروف',
            'mini_price.between' => 'يجب ان يكون أقل سعر للمنتج يتراوح بين 1 الي 100000 جنيه',

            'Wholesale_Price.required' => 'يرجي ادخال سعر الجمله',
            'Wholesale_Price.numeric' => 'يجب ان يكون سعر الحمله مكون من ارقام فقط وليس حروف',
            'Wholesale_Price.between' => 'يجب ان يكون سعر الجمله يتراوح بين 0 الي 100000 جنيه',

            'quantity.required' => 'يرجي ادخال كمية المنتج ',
            'quantity.numeric' => 'يجب ان يكون كمية المنتج  مكون من ارقام فقط وليس حروف',
            'quantity.between' => 'يجب ان يكون كمية المنتج يتراوح بين 0 الي 100000 جنيه',
        ]);

        $products = Product::find($id);
        $products->update([
            // 'Product_code'=>$request->Product_code,
            'Product_name' => $request->Product_name,
            'description' => $request->description,
            'price' => $request->price,
            'mini_price'=>$request->mini_price,
            'Wholesale_Price'=>$request->Wholesale_Price,
            'quantity'=>$request->quantity,
            'section_id' => $request->section_id,
            'user' => (Auth::user()->name),
        ]);
        session()->flash('edit', 'تم تعديل المنتج بنجاج');
        return redirect('/products');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $Product = Product::find($request->id)->delete();
        session()->flash('delete', 'تم حذف المنتج بنجاح');
        return redirect('/products');
    }
}
