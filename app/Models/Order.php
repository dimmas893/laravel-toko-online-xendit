<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table='order';
    protected $guarded=[];

    public function transaksi(){
    	return $this->belongsTo(Transaksi::class);
    }

    public function produk(){
    	return $this->belongsTo(Produk::class);
    }
}
