<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Invoice extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
        'invoice_number',
        'order_id',
        'invoice_Date',
        // 'Due_date',
        // 'product',
        // 'section_id',
        // 'Amount_collection',
        // 'Amount_Commission',
        // 'Discount',
        // 'Value_VAT',
        // 'Rate_VAT',
        'Total',
        'partial',
        'Status',
        'Value_Status',
        'client',
        'phoneClient',
        'note',
        'Payment_Date',
    ];
    public function section(){
        return $this->belongsTo('App\Models\Section','section_id');
    }
    public function invoice_details(){
        return $this->hasOne('App\Models\InvoiceDetails','id_Invoice');
    }
    public function order(){
        return $this->belongsTo('App\Models\Order','order_id');
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            $year = now()->format('Y');
            $lastInvoice = static::whereYear('created_at', $year)->latest()->first();

            if (!$lastInvoice) {
                $invoice->invoice_number = 'INV-'.$year .'-'. '1';
            } else {
                $expNum = explode('-', $lastInvoice->invoice_number);
                // return $expNum;
                $nextInvoiceNumber = $expNum[0].'-'.$expNum[1] . '-' . $expNum[2] + 1;
                // $lastInvoiceNumber = intval(substr($lastInvoice->invoice_number, 4));
                // $newInvoiceNumber = str_pad($lastInvoiceNumber + 1, 5, '0', STR_PAD_LEFT);
                $invoice->invoice_number = $nextInvoiceNumber;
            }
        });
    }
}
