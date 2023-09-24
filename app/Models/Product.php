<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable =[
        'Product_name',
        'description',
        'price',
        'mini_price',
        'Wholesale_Price',
        'quantity',
        'section_id',
        'user',
    ];
    public function section(){
        return $this->belongsTo('App\Models\Section','section_id');
    }
}
