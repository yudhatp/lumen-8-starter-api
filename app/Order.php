<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{

    protected $table = "orders";
    protected $fillable = [
        'order_number', 'order_date', 'total_qty', 'total_price',
        'created_at', 'updated_at'
    ];

    protected $hidden = [];
}