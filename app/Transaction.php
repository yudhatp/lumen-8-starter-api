<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{

    public $timestamps = false;
    protected $table = "transaction";
    protected $fillable = [
        'no_nota', 'tgl_pesan', 'tgl_selesai', 'nama_pelanggan',
        'no_telpon', 'total_harga', 'jumlah', 'status'
    ];

    protected $hidden = [];
}