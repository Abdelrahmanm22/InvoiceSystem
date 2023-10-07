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
                $nextInvoiceNumber = $expNum[0].'-'.$expNum[1] . '-' . $expNum[2] + 1;
                $invoice->invoice_number = $nextInvoiceNumber;
            }
        });
    }

    public function scopeLastFiveMonthsTotal($query)
    {
        return $query
            ->selectRaw('YEAR(invoice_Date) as year, MONTH(invoice_Date) as month, SUM(Total) as total')
            ->whereBetween('invoice_Date', [now()->subMonths(4)->startOfMonth(), now()->endOfMonth()])
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc');
    }
}
