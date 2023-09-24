<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'phone',
        'payment_type',
    ];
    public function order_details(){
        return $this->hasMany(OrderDetail::class);
    }
    public function invoice(){
        return $this->hasOne('App\Models\Invoice');
    }
}

