<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{

    protected $table = "order_details";
    protected $fillable = [
        'order_number', 'category_name', 'price','qty','subtotal',
        'created_at', 'updated_at'
    ];

    protected $hidden = [];
}