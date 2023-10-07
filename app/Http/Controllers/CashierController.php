<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\InvoiceDetails;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use App\Models\Safe;
use App\Models\Section;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Notifications\InvoiceNotification;
use Illuminate\Support\Facades\Notification;

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
    public function getProductDetails($product)
    {
        $productData = Product::where('Product_name', $product)->first();
        if ($productData) {
            $name = $productData->Product_name;
            $price = $productData->price;
            $miniPrice = $productData->mini_price;
            $mount = $productData->quantity;
            $section = $productData->section->section_name;

            return response()->json([
                'section' => $section,
                'name' => $name,
                'price' => $price,
                'mini_price' => $miniPrice,
                'mount' => $mount,

            ]);
        } else {
            // Handle the case where the product with the given ID is not found.
            return response()->json(['error' => 'Product not found'], 404);
        }
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
        $product = Product::find($id);
        if ($product) {
            return Product::find($id)->mini_price;
        } else {
            session()->flash('notFound');
            return back();
        }
    }
    public function getMostQuantity($id)
    {
        return Product::find($id)->quantity;
    }

    public function sendNotification($invoice)
    {
        $users = User::all()->except(Auth::id()); ///send to all users
        Notification::send($users, new InvoiceNotification($invoice));
    }

    public function store_order(Request $request)
    {
        // return $request;

        $mp = array();
        foreach ($request['products'] as $i => $product) {
            $mount = $request['mounts'][$i];
            if (!isset($mp[$product])) {
                $mp[$product] = 0;
            }
            $mp[$product] += $mount;
        }

        for ($i = 0; $i <= count($request->products) - 1; $i++) {
            $productData = Product::where('Product_name', $request->products[$i])->first();
            if (!$productData) {
                session()->flash('notFound');
                return back();
            }
            $minimumProductPrice = $this->getMinPrice($productData->id);
            if ($request->prices[$i] < $minimumProductPrice) {
                session()->flash('errorPrice');
                return back();
            }
            if ($request->mounts[$i] < 1) {
                session()->flash('errorMount');
                return back();
            }
            $mostQuantity = $this->getMostQuantity($productData->id);
            if ($mp[$request->products[$i]] > $mostQuantity) {
                session()->flash('mostMount');
                return back();
            }
        }
        $total = 0;
        for ($i = 0; $i <= count($request->products) - 1; $i++) {
            $product = Product::where('Product_name', $request->products[$i])->first();
            if (!$product) {
                session()->flash('notFound');
                return back();
            }
            $total += $request->prices[$i] * $request->mounts[$i];
        }
        if ($request->status == 3) {
            if ($request->partialPayment >= $total) {
                session()->flash('partialMath');
                return back();
            } else if ($request->partialPayment <= 0 or !$request->partialPayment) {
                session()->flash('partialError');
                return back();
            }
        }
        $order = Order::create([
            'payment_type' => 'cashe',
        ]);

        for ($i = 0; $i <= count($request->products) - 1; $i++) {
            $product = Product::where('Product_name', $request->products[$i])->first();
            $product->update([
                'quantity' => $product->quantity - $request->mounts[$i],
            ]);
            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'section' => $request->sections[$i],
                'mount' => $request->mounts[$i],
                'total' => $request->prices[$i] * $request->mounts[$i],
            ]);
        }
        // return $total;
        $validator = Validator::make($request->all(), [
            'client' => 'max:100',
            'phoneClient' => 'nullable|digits:11',
        ], [
            'client.max' => 'يجب ان لا يزيد اسم العميل عن  100 حرف',
            'phoneClient.digits' => 'يجب ان يكون رقم العميل مكون من 11 رقم',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $pay_status = "";
        if ($request->status == 1) {
            $pay_status = "مدفوعة";
            Invoice::create([
                'order_id' => $order->id,
                'invoice_Date' => Carbon::now(),
                'Total' => $total,
                'partial' => $total,
                'Status' => $pay_status,
                'client' => $request->client,
                'phoneClient' => $request->phoneClient,
                'Value_Status' => $request->status,
                'note' => $request->note,
            ]);
            $mySafe = Safe::get()->first();
            $safeMoney = $mySafe->money;
            $mySafe->update([
                'money' => $safeMoney + $total,
            ]);
            ///save to InvoiceDetails//////////////
            $invoice = Invoice::latest()->first();
            $this->sendNotification($invoice);
            InvoiceDetails::create([
                'id_Invoice' => $invoice->id,
                'invoice_number' => $order->id,
                'Status' => $pay_status,
                'partial' => $total,
                'Value_Status' => $request->status,
                'note' => $request->note,
                'user' => (Auth::user()->name),
            ]);
        } else if ($request->status == 2) {
            $pay_status = "غير مدفوعة";
            Invoice::create([
                'order_id' => $order->id,
                'invoice_Date' => Carbon::now(),
                'Total' => $total,
                'partial' => 0,
                'Status' => $pay_status,
                'client' => $request->client,
                'phoneClient' => $request->phoneClient,
                'Value_Status' => $request->status,
                'note' => $request->note,
            ]);
            // $mySafe = Safe::get()->first();
            // $safeMoney = $mySafe->money;
            // $mySafe->update([
            //     'money' => $safeMoney + $total,
            // ]);
            ///save to InvoiceDetails//////////////
            $invoice = Invoice::latest()->first();
            $this->sendNotification($invoice);
            InvoiceDetails::create([
                'id_Invoice' => $invoice->id,
                'invoice_number' => $order->id,
                'Status' => $pay_status,
                'partial' => 0,
                'Value_Status' => $request->status,
                'note' => $request->note,
                'user' => (Auth::user()->name),
            ]);
        } else {
            $pay_status = "مدفوعة جزئيا";
            Invoice::create([
                'order_id' => $order->id,
                'invoice_Date' => Carbon::now(),
                'Total' => $total,
                'partial' => $request->partialPayment,
                'Status' => $pay_status,
                'client' => $request->client,
                'phoneClient' => $request->phoneClient,
                'Value_Status' => $request->status,
                'note' => $request->note,
            ]);


            $mySafe = Safe::get()->first();
            $safeMoney = $mySafe->money;
            $mySafe->update([
                'money' => $safeMoney + $request->partialPayment,
            ]);
            ///save to InvoiceDetails//////////////
            $invoice = Invoice::latest()->first();
            $this->sendNotification($invoice);
            InvoiceDetails::create([
                'id_Invoice' => $invoice->id,
                'invoice_number' => $order->id,
                'Status' => $pay_status,
                'Value_Status' => $request->status,
                'partial' => $request->partialPayment,
                'note' => $request->note,
                'user' => (Auth::user()->name),
            ]);
        }

        $orderbacks = $order->order_details;
        session()->flash('success');
        session()->flash('orderbacks', $orderbacks);
        return redirect()->route('cashier');
    }
}
