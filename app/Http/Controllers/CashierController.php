<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceDetails;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Safe;
use App\Models\Section;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class CashierController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:الكاشير', ['only' => ['index']]);
        $this->middleware('permission:تخزين طلب', ['only' => ['store_order']]);
    }
    public function index()
    {
        return view('cashier.cashier');
    }
    public function get_products($section_id)
    {
        return response()->json(Section::find($section_id)->products->pluck('id', 'Product_name'));
    }
    public function get_price($product)
    {
        $productData = Product::find($product);

        if ($productData) {
            $price = $productData->price;
            $miniPrice = $productData->mini_price;
            $mount = $productData->quantity;

            return response()->json([
                'price' => $price,
                'mini_price' => $miniPrice,
                'mount' => $mount,
            ]);
        } else {
            // Handle the case where the product with the given ID is not found.
            return response()->json(['error' => 'Product not found'], 404);
        }
    }
    public function getMinPrice($id)
    {
        return Product::find($id)->mini_price;
    }
    public function getMostQuantity($id)
    {
        return Product::find($id)->quantity;
    }
    // public function ValidationToBuy(Request $request){

    // }
    public function store_order(Request $request)
    {
        
        $mp = array();
        foreach ($request['products'] as $i => $product) {
            $mount = $request['mounts'][$i];
            if (!isset($mp[$product])) {
                $mp[$product] = 0;
            }
            $mp[$product] += $mount;
        }
        for ($i = 0; $i <= count($request->products) - 1; $i++) {
            $minimumProductPrice = $this->getMinPrice($request->products[$i]);
            if ($request->prices[$i] < $minimumProductPrice) {
                session()->flash('errorPrice');
                return back();
            }
            if ($request->mounts[$i] < 1) {
                session()->flash('errorMount');
                return back();
            }
            $mostQuantity = $this->getMostQuantity($request->products[$i]);
            if ($mp[$request->products[$i]] > $mostQuantity) {
                session()->flash('mostMount');
                return back();
            }
        }
        $order = Order::create([
            'payment_type' => 'cashe',
        ]);
        // return $request->status;
        ///check availability
        
        // dd($mp[$request->products[0]]);
        // return  $order;

        $total = 0;
        for ($i = 0; $i <= count($request->products) - 1; $i++) {
            $product = Product::find($request->products[$i]);
            $product->update([
                'quantity' => $product->quantity - $request->mounts[$i],
            ]);
            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $request->products[$i],
                'section' => Section::find($request->sections[$i])->section_name,
                'mount' => $request->mounts[$i],
                'total' => $request->prices[$i] * $request->mounts[$i],
            ]);
            $total += $request->prices[$i];
        }
        $validator = Validator::make($request->all(), [
            'client'=>'max:100',
            'phoneClient'=>'nullable|digits:11',
        ], [
            'client.max'=>'يجب ان لا يزيد اسم العميل عن  100 حرف',
            'phoneClient.digits'=>'يجب ان يكون رقم العميل مكون من 11 رقم',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $pay_status = "";
        if ($request->status == 1) {
            $pay_status = "مدفوعة";
        } else if ($request->status == 2) {
            $pay_status = "غير مدفوعة";
        } else {
            $pay_status = "مدفوعة جزئيا";
        }
        Invoice::create([
            'order_id' => $order->id,
            'invoice_Date' => Carbon::now(),
            // 'Due_date' => $request->Due_date,
            // 'product' => $request->product,
            // 'section_id' => $request->Section,
            // 'Amount_collection' => $request->Amount_collection,
            // 'Amount_Commission' => $request->Amount_Commission,
            // 'Discount' => $request->Discount,
            // 'Value_VAT' => $request->Value_VAT,
            // 'Rate_VAT' => $request->Rate_VAT,
            'Total' => $total,
            'Status' => $pay_status,
            'client'=>$request->client,
            'phoneClient'=>$request->phoneClient,
            'Value_Status' => $request->status,
            'note' => $request->note,
        ]);
        $mySafe=Safe::get()->first();
        // return $mySafe;
        $safeMoney = $mySafe->money;
        $mySafe->update([
            'money'=>$safeMoney+$total,
        ]);

        ///save to InvoiceDetails//////////////
        $invoice_id = Invoice::latest()->first()->id;
        InvoiceDetails::create([
            'id_Invoice' => $invoice_id,
            'invoice_number' => $order->id,
            // 'product' => $request->product,
            // 'Section' => $request->Section,
            'Status' => $pay_status,
            'Value_Status' => $request->status,
            'note' => $request->note,
            'user' => (Auth::user()->name),
        ]);

        $orderbacks = $order->order_details;
        session()->flash('success');
        session()->flash('orderbacks', $orderbacks);
        return redirect()->route('cashier');
    }
}
