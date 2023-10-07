<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use Illuminate\Database\Eloquent\SoftDeletes;
class Product extends Model
{
    use HasFactory;
    // use SoftDeletes;
    protected $fillable =[
        // 'Product_code',
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
