<?php

namespace App\Http\Controllers;

use Date;
use DateTime;
use App\Transaksi;
use App\TransaksiDetail;
use App\TransaksiStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrackingController extends Controller
{
    
    public function trackingByNota($nota)
    {
        $header = Transaksi::where('no_nota',$nota)->get();
        if(count($header)>0){
            $detail = TransaksiDetail::where('no_nota',$nota)->get();
            $status = TransaksiStatus::where('no_nota',$nota)->orderBy('id','desc')->get();
            return response()->json([
                'message'   =>'ok',
                'header'    => $header,
                'detail'    => $detail,
                'status'    => $status
            ], 200);
        }else{
            return response()->json([
                'message'   =>'Data Tidak Ditemukan'
            ], 200);
        }
    }
}