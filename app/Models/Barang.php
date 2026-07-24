<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';
    protected $fillable = ['nama_barang', 'harga', 'stok'];

    public function penjualan()
    {
        return $this->hasMany(Penjualan::class);
    }
}
