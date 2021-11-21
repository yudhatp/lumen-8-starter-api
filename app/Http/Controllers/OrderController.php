<?php

namespace App\Http\Controllers;

use Date;
use DateTime;
use App\Category;
use App\Order;
use App\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($skip)
    {
        return response()->json(Order::orderBy('order_number')->skip($skip)->take(4)->get());
    }

    public function detail($order_number)
    {
        $data = Order::where('order_number',$order_number)->get();
        return response()->json($data[0]);
    }

    public function searchOrder($order_number)
    {
        $data = Order::where('order_number','like',$order_number.'%')->get();
        return response()->json($data);
    }
    
    public function detailItem($order_number)
    {
        return response()->json(OrderDetail::where('order_number',$order_number)->orderBy('id','asc')->get());
    }

    public function create(Request $request)
    {
        $order = new Order;
        $order->order_date = $request->order_date;
        $order->total_price= 0;
        $order->total_qty = 0;
        $order->save();

        $new_order = Order::findOrFail($order->id);
        return response()->json([
            'message'   =>'ok',
            'order'     => $new_order
        ], 200);
    }

    public function createDetail(Request $request)
    {
        //get category name & recent price
        $category = Category::where('id',$request->id_category)->get(['price','category_name']);

        //check if category already exist
        $items = OrderDetail::where('order_number', $request->order_number)
                            ->where('category_name', $category[0]->category_name)
                            ->get(['price','qty']);
        
        if(count($items) > 0){
            //update detail
            OrderDetail::where('order_number',$request->order_number)
                        ->where('category_name', $category[0]->category_name)
                        ->update([
                            'qty' => $items[0]->qty + 1, 
                            'subtotal' => ($items[0]->price * ($items[0]->qty + 1))
                        ]);
        }else{
            //create new detail
            $order = new OrderDetail;
            $order->order_number = $request->order_number;
            $order->category_name = $category[0]->category_name;
            $order->price = $category[0]->price;
            $order->qty = $request->qty;
            $order->subtotal = ($category[0]->price * $request->qty);
            $order->save();
        }

        //calculate summary orders
        $total_price = OrderDetail::where('order_number',$request->order_number)->sum('subtotal');
        $qty = OrderDetail::where('order_number',$request->order_number)->sum('qty');
        
        Order::where('order_number',$request->order_number)
             ->update([
                 'total_price' => $total_price, 
                 'total_qty' => $qty
            ]);

        return response()->json([
            'message'   =>'ok'
        ], 200);
    }

    
    public function delete(Request $request)
    {
        OrderDetail::where('order_number',$request->order_number)->delete();
        Order::where('order_number',$request->order_number)->delete();
        return response()->json(['message'=>'ok'], 200);
    }

    public function deleteDetail(Request $request)
    {
        OrderDetail::where('id',$request->id)->delete();

        $total_price = OrderDetail::where('order_number',$request->order_number)->sum('subtotal');
        $qty = OrderDetail::where('order_number',$request->order_number)->sum('qty');

        Order::where('order_number',$request->order_number)
             ->update([
                 'total_price' => $total_price,
                 'total_qty' => $qty
            ]);
        return response()->json(['message'=>'ok'], 200);
    }
}