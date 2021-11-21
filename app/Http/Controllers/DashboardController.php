<?php

namespace App\Http\Controllers;

use App\Order;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function recentOrder()
    {
        return response()->json(Order::orderBy('order_number')->limit(5)->get());
    }

    public function totalOrder()
    {
        $total = Order::count();
        return response()->json([
            'message'       =>'ok',
            'total_order'   => $total
        ], 200);
    }

    public function totalCategory()
    {
        $total = Category::count();
        return response()->json([
            'message'          =>'ok',
            'total_category'   => $total
        ], 200);
    }
}