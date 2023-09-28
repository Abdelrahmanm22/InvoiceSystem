<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceDetails extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_Invoice',
        'invoice_number',
        // 'product',
        // 'Section',
        'partial',
        'Status',
        'Value_Status',
        'note',
        'user',
        'Payment_Date',
    ];
    public function invoice(){
        return $this->belongsTo('App\Models\invoices','id_Invoice');
    }
}
