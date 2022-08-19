<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Blameable;
class Produk extends Model
{
    use HasFactory;
    protected $table='produk';
    protected $guarded=[];
    use SoftDeletes;
    use Blameable;
    public function stock(){
    	return $this->hasMany(Stock::class)->orderBy('created_at','desc');
    }

    public function kategori(){
    	return $this->belongsTo(Kategori::class)->withTrashed();
    }

    public function order(){
    	return $this->hasMany(Order::class);
    }
}
