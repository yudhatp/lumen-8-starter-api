<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TransaksiDetail extends Model
{

    public $timestamps = false;
    protected $table = "detail_transaksi";
    protected $fillable = [
        'no_nota', 'nama_kategori', 'harga','qty',
        'ukuran','keterangan','subtotal'
    ];

    protected $hidden = [];
}