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

    public function index()
    {
        return response()->json(Order::all());
    }

    public function detail($order_number)
    {
        $data = Order::where('order_number',$order_number)->get();
        return response()->json($data[0]);
    }

    public function detailItem($order_number)
    {
        return response()->json(OrderDetail::where('order_number',$order_number)->orderBy('id','asc')->get());
    }

    public function belumSelesai()
    {
        return response()->json(Order::whereRaw("status not in('Lunas','Batal')")->get());
    }

    public function selesai()
    {
        return response()->json(Order::whereRaw("status ='Lunas'")->orderBy('tgl_selesai')->get());
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
        $category = Category::where('id',$request->id_category)->get(['price','category_name']);
        
        $order = new OrderDetail;
        $order->order_number = $request->order_number;
        $order->category_name = $category[0]->category_name;
        $order->price = $category[0]->price;
        $order->qty = $request->qty;
        $order->subtotal = ($category[0]->price * $request->qty);
        $order->save();

        $total_price = OrderDetail::where('order_number',$request->order_number)->sum('subtotal');
        $qty = OrderDetail::where('order_number',$request->order_number)->sum('qty');
        
        Order::where('order_number',$request->order_number)->update(
            ['total_price' => $total_price, 'total_qty' => $qty]);
        return response()->json([
            'message'   =>'ok'
        ], 200);
    }

    
    public function delete(Request $request)
    {
        OrderDetail::where('order_number',$request->no)->delete();
        Order::where('order_number',$request->no)->delete();
        return response()->json(['message'=>'ok'], 200);
    }

    public function deleteDetail(Request $request)
    {
        OrderDetail::where('id',$request->id)->delete();

        $total_price = OrderDetail::where('order_number',$request->no)->sum('subtotal');
        $qty = OrderDetail::where('order_number',$request->no)->sum('qty');

        Order::where('order_number',$request->no)
            ->update(['total_price' => $total_price,'qty' => $qty]);
        return response()->json(['message'=>'ok'], 200);
    }
}