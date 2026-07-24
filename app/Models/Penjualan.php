<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $table = 'penjualan';
    protected $fillable = ['barang_id', 'jumlah', 'total_harga'];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
}
