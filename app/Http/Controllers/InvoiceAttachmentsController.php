<?php

namespace App\Http\Controllers;

use App\Models\InvoiceAttachments;
use App\Traits\attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InvoiceAttachmentsController extends Controller
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
    public function index($invoice_number, $file_name)
    {
        $st = "Attachment";
        $pathToFile = public_path($st . '/' . $invoice_number . '/' . $file_name);
        return response()->file($pathToFile);
    }


    public function download($invoice_number, $file_name)
    {
        $st = "Attachment";
        $pathToFile = public_path($st . '/' . $invoice_number . '/' . $file_name);
        $headers = array(
            'Content-Type: application/pdf',
        );
        return response()->download($pathToFile, $file_name, $headers);
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
        $this->validate($request, [

            'file_name' => 'mimes:pdf,jpeg,png,jpg',

        ], [
            'file_name.mimes' => 'صيغة المرفق يجب ان تكون   pdf, jpeg , png , jpg',
        ]);

        $invoice_number = $request->invoice_number;
        $image_file_name = $this->save(
            $request->file('file_name'),
            'attachment/' . $invoice_number
        );
        $attachments = new InvoiceAttachments();
        $attachments->file_name = $image_file_name;
        $attachments->invoice_number = $invoice_number;
        $attachments->Created_by = Auth::user()->name;
        $attachments->invoice_id = $request->invoice_id;
        $attachments->save();
        session()->flash('Add', 'تم اضافة المرفق بنجاح');
        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InvoiceAttachments  $invoiceAttachments
     * @return \Illuminate\Http\Response
     */
    public function show(InvoiceAttachments $invoiceAttachments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InvoiceAttachments  $invoiceAttachments
     * @return \Illuminate\Http\Response
     */
    public function edit(InvoiceAttachments $invoiceAttachments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InvoiceAttachments  $invoiceAttachments
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InvoiceAttachments $invoiceAttachments)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InvoiceAttachments  $invoiceAttachments
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Storage::disk('public_uploads')->delete($request->invoice_number.'/'.$request->file_name);
        $invoices = InvoiceAttachments::findOrFail($request->id_file);
        $invoices->delete();
        
        session()->flash('delete', 'تم حذف المرفق بنجاح');
        return back();
    }
}