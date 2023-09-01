<?php

namespace App\Http\Controllers;

use App\Exports\InvoicesExport;
use App\Models\Invoice;
use App\Models\InvoiceAttachments;
use App\Models\InvoiceDetails;
use App\Models\Section;
use App\Models\User;
use App\Notifications\addInvoice;
use App\Traits\attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use App\Exports\UsersExport;
use App\Notifications\InvoiceNotification;
use Maatwebsite\Excel\Facades\Excel;
class InvoiceController extends Controller
{
    use attachment;

    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $invoices = Invoice::get();
        return view('invoices.invoices', compact('invoices'));
    }


    public function Invoice_Paid()
    {
        $invoices = Invoice::where('Value_Status', 1)->get();
        return view('invoices.invoices_paid',compact('invoices'));
    }

    public function Invoice_unPaid()
    {
        $invoices = Invoice::where('Value_Status',2)->get();
        return view('invoices.invoices_unpaid',compact('invoices'));
    }

    public function Invoice_Partial()
    {
        $invoices = Invoice::where('Value_Status',3)->get();
        return view('invoices.invoices_Partial',compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $sections = Section::get();
        return view('invoices.addInvoice', compact('sections'));
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
            'invoice_number' => 'required|unique:invoices',
            'invoice_Date' => 'required',
            'Due_date' => 'required',
            'product' => 'required',
            'Section' => 'required',
            'Amount_collection' => 'required',
            'Amount_Commission' => 'required',
            'Discount' => 'required',
            'Rate_VAT' => 'required',
        ], [
            'invoice_number.required' => 'يرجي ادخال رقم الفاتوره',
            'invoice_number.unique' => 'رقم الفاتوره مسجل مسبقا',
            'invoice_Date.required' => 'يرجي ادخال  تاريخ الفاتوره',
            'Due_date.required' => 'يرجي ادخال تاريخ الاستحقاق',
            'product.required' => 'يرجي ادخال اسم المنتج',
            'Section.required' => 'يرجي ادخال اسم القسم',
            'Amount_collection.required' => 'يرجي ادخال مبلغ التحصيل',
            'Amount_Commission.required' => 'يرجي ادخال مبلغ العموله',
            'Discount.required' => 'يرجي ادخال الخصم',
            'Rate_VAT.required' => 'يرجي ادخال نسبة ضريبة القيمه المضافه',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        

        Invoice::create([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
        ]);

        ///save to InvoiceDetails//////////////
        $invoice_id = Invoice::latest()->first()->id;
        InvoiceDetails::create([
            'id_Invoice' => $invoice_id,
            'invoice_number' => $request->invoice_number,
            'product' => $request->product,
            'Section' => $request->Section,
            'Status' => 'غير مدفوعة',
            'Value_Status' => 2,
            'note' => $request->note,
            'user' => (Auth::user()->name),
        ]);



        ///save to InvoiceAttachments if picture exists//////////////
        if ($request->hasFile('pic')) {
            $invoice_number = $request->invoice_number;
            $image_file_name = $this->save(
                $request->file('pic'),
                'attachment/' . $invoice_number
            );
            $attachments = new InvoiceAttachments();
            $attachments->file_name = $image_file_name;
            $attachments->invoice_number = $invoice_number;
            $attachments->Created_by = Auth::user()->name;
            $attachments->invoice_id = $invoice_id;
            $attachments->save();
        }

        //mail notification
        $user = Auth::user();
        Notification::send($user, new addInvoice($invoice_id));
        // $user->notify(new addInvoice($invoice_id));



        //normal notification
        $invoice = Invoice::latest()->first();
        $users = User::all()->except(Auth::id()); ///send to all users
        Notification::send($users, new InvoiceNotification($invoice));


        session()->flash('Add', 'تم اضافة فاتوره بنجاح ');
        return redirect('/addInvoices');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $invoice = Invoice::where('id', $id)->first();
        $sections = Section::get();
        return view('invoices.update', compact('invoice', 'sections'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        // 'section_name' => 'required|max:255|unique:sections,section_name,' . $id,
        $id = $request->invoice_id;
        $validator = Validator::make($request->all(), [
            'invoice_number' => 'required|unique:invoices,invoice_number,' . $id,
            'invoice_Date' => 'required',
            'Due_date' => 'required',
            'product' => 'required',
            'Section' => 'required',
            'Amount_collection' => 'required',
            'Amount_Commission' => 'required',
            'Discount' => 'required',
            'Rate_VAT' => 'required',
        ], [
            'invoice_number.required' => 'يرجي ادخال رقم الفاتوره',
            'invoice_number.unique' => 'رقم الفاتوره مسجل مسبقا',
            'invoice_Date.required' => 'يرجي ادخال  تاريخ الفاتوره',
            'Due_date.required' => 'يرجي ادخال تاريخ الاستحقاق',
            'product.required' => 'يرجي ادخال اسم المنتج',
            'Section.required' => 'يرجي ادخال اسم القسم',
            'Amount_collection.required' => 'يرجي ادخال مبلغ التحصيل',
            'Amount_Commission.required' => 'يرجي ادخال مبلغ العموله',
            'Discount.required' => 'يرجي ادخال الخصم',
            'Rate_VAT.required' => 'يرجي ادخال نسبة ضريبة القيمه المضافه',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        $invoices = Invoice::findOrFail($id);
        $invoices->update([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'Due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'Amount_collection' => $request->Amount_collection,
            'Amount_Commission' => $request->Amount_Commission,
            'Discount' => $request->Discount,
            'Value_VAT' => $request->Value_VAT,
            'Rate_VAT' => $request->Rate_VAT,
            'Total' => $request->Total,
            'note' => $request->note,
        ]);

        $invoiceDetails = InvoiceDetails::where('id_Invoice', $id);
        $invoiceDetails->update([
            'product' => $request->product,
            'Section' => $request->Section,
            'note' => $request->note,
            'user' => (Auth::user()->name),
        ]);

        session()->flash('edit', 'تم تعديل الفاتورة بنجاح');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $invoice = Invoice::where('id', $request->invoice_id)->first();
        $Details = InvoiceAttachments::where('invoice_id', $request->invoice_id)->first();

        if (!empty($Details->invoice_number)) {
            Storage::disk('public_uploads')->deleteDirectory($Details->invoice_number);
        }
        $invoice->forceDelete();
        session()->flash('delete_invoice');
        return redirect('/invoices');
    }

    public function archive(Request $request)
    {
        $invoice = Invoice::where('id', $request->invoice_id)->first();
        $invoice->delete();
        session()->flash('archive_invoice');
        return redirect('/Archive');
    }

    public function changePayment($id){
        $invoice = Invoice::where('id', $id)->first();
        return view('invoices.updatePayment',compact('invoice'));
    }

    public function postChangePayment(Request $request){
        $id = $request->invoice_id;
        $invoices = Invoice::findOrFail($id);

        if ($request->Status === 'مدفوعة') {

            $invoices->update([
                'Value_Status' => 1,
                'Status' => $request->Status,
                'Payment_Date' => $request->Payment_Date,
            ]);

            InvoiceDetails::create([
                'id_Invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => $request->Status,
                'Value_Status' => 1,
                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
        }

        else {
            $invoices->update([
                'Value_Status' => 3,
                'Status' => $request->Status,
                'Payment_Date' => $request->Payment_Date,
            ]);
            InvoiceDetails::create([
                'id_Invoice' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => $request->Status,
                'Value_Status' => 3,
                'note' => $request->note,
                'Payment_Date' => $request->Payment_Date,
                'user' => (Auth::user()->name),
            ]);
        }
        session()->flash('Status_Update');
        return redirect('/invoices');

    }

    public function printInvoice($id){
        $invoice = Invoice::findOrFail($id);
        return view('invoices.printInvoice',compact('invoice'));
    }

    public function export() 
    {
        return Excel::download(new InvoicesExport, 'Invoices.xlsx');
    }

    public function MarkAsRead_all(){
        $userUnreadNotification= auth()->user()->unreadNotifications;

        if($userUnreadNotification) {
            $userUnreadNotification->markAsRead();
            return back();
        }
    }

    public function MarkAsRead($id,$nty){
        $userUnreadNotification= auth()->user()->unreadNotifications;
        if($userUnreadNotification) {

            foreach ($userUnreadNotification as $notification) {
                if ($notification->id == $nty) {
                    $notification->markAsRead();
                    return redirect()->route('InvoicesDetails', ['id' => $id]);
                }
            }
            
            
        }
        return back();

    }

}
