<?php

namespace App\Http\Controllers;

use Date;
use DateTime;
use App\Kategori;
use App\Transaction;
use App\TransactionDetail;
use App\TransactionStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return response()->json(Transaction::all());
    }

    public function detail($nota)
    {
        $data = Transaction::where('no_nota',$nota)->get();
        return response()->json($data[0]);
    }

    public function detailStatus($nota)
    {
        return response()->json(TransactionStatus::where('no_nota',$nota)->orderBy('tgl_status','desc')->get());
    }

    public function detailItem($nota)
    {
        return response()->json(TransactionDetail::where('no_nota',$nota)->orderBy('id','asc')->get());
    }

    public function belumSelesai()
    {
        return response()->json(Transaction::whereRaw("status not in('Lunas','Batal')")->get());
    }

    public function selesai()
    {
        return response()->json(Transaction::whereRaw("status ='Lunas'")->orderBy('tgl_selesai')->get());
    }

    public function totalPendapatan()
    {
        $total = Transaction::whereRaw("status ='Lunas' and date_format(tgl_selesai,'%Y-%m-%d')=curdate()")->sum('total_harga');
        return response()->json(['message' => 'ok', 'total' => $total]);
    }

    public function getNoNota()
    {
        $data = DB::select('select replace(curdate(),\'-\',\'\') as tgl, 
        ifnull(max(no_nota),\'-\') as nomor from transaction 
        where left(no_nota,8) =replace(curdate(),\'-\',\'\')');
        if($data[0]->nomor == "-"){
            $nomor = date('Ymd')."0001";
        }else{
            $nomor = $data[0]->nomor+1;
        }
        return $nomor;
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'nama_pelanggan' => 'required|string',
            'no_telpon' => 'required|string'
        ]);
        //$transaction = Transaction::create($request->all());
        $nota = $this->getNoNota();
        $transaction = new Transaction;
        $transaction->no_nota = $nota;
        $transaction->nama_pelanggan = $request->nama_pelanggan;
        $transaction->no_telpon = $request->no_telpon;
        $transaction->tgl_pesan = date('Y-m-d H:i');
        $transaction->total_harga = 0;
        $transaction->jumlah = 0;
        $transaction->status = 'Belum Diproses';
        $transaction->save();

        $status = new TransactionStatus;
        $status->no_nota = $nota;
        $status->status = 'Belum Diproses';
        $status->tgl_status = date('Y-m-d H:i');
        $status->save();
        return response()->json([
            'message'   =>'ok',
            'nota'      => $nota
        ], 200);
    }

    public function createDetail(Request $request)
    {
        $kategori = Kategori::where('id',$request->id_kategori)->get(['harga','nama_kategori']);
        
        $transaction = new TransactionDetail;
        $transaction->no_nota = $request->nota;
        $transaction->nama_kategori = $kategori[0]->nama_kategori;
        $transaction->harga = $kategori[0]->harga;
        $transaction->qty = $request->qty;
        $transaction->ukuran = $request->ukuran;
        $transaction->keterangan = $request->keterangan;
        $transaction->subtotal = ($kategori[0]->harga * $request->qty);
        $transaction->save();

        $total_harga = TransactionDetail::where('no_nota',$request->nota)->sum('subtotal');
        $jumlah = TransactionDetail::where('no_nota',$request->nota)->sum('qty');
        
        Transaction::where('no_nota',$request->nota)->update(
            ['total_harga' => $total_harga, 'jumlah' => $jumlah]);
        return response()->json([
            'message'   =>'ok'
        ], 200);
    }

    public function createStatus(Request $request)
    {
        $transaction = new TransactionStatus;
        $transaction->no_nota = $request->nota;
        $transaction->status = $request->status;
        $transaction->tgl_status = date('Y-m-d H:i');
        $transaction->save();

        if($request->status == "Lunas"){
            Transaction::where('no_nota',$request->nota)
            ->update(['status' => $request->status,'tgl_selesai' => date('Y-m-d H:i')]);
        }else{
            Transaction::where('no_nota',$request->nota)->update(['status' => $request->status]);
        }
        return response()->json([
            'message'   =>'ok'
        ], 200);
    }

    public function delete(Request $request)
    {
        TransactionDetail::where('no_nota',$request->nota)->delete();
        TransactionStatus::where('no_nota',$request->nota)->delete();
        Transaction::where('no_nota',$request->nota)->delete();
        return response()->json(['message'=>'ok'], 200);
    }

    public function deleteDetail(Request $request)
    {
        TransactionDetail::where('id',$request->id)->delete();

        $total_harga = TransactionDetail::where('no_nota',$request->nota)->sum('subtotal');
        $jumlah = TransactionDetail::where('no_nota',$request->nota)->sum('qty');

        Transaction::where('no_nota',$request->nota)
            ->update(['total_harga' => $total_harga,'jumlah' => $jumlah]);
        return response()->json(['message'=>'ok'], 200);
    }

    public function deleteStatus(Request $request)
    {
        TransactionStatus::where('id',$request->id)->delete();

        $status = TransactionStatus::where('no_nota',$request->nota)
        ->orderBy('id','desc')->first();

        Transaction::where('no_nota',$request->nota)
            ->update(['status' => $status->status]);
        return response()->json(['message'=>'ok'], 200);
    }
}