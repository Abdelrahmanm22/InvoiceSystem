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
}
