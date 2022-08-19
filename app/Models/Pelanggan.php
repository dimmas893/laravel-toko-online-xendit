<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Blameable;
class Pelanggan extends Model
{
    use HasFactory;
    protected $table='pelanggan';
    protected $guarded=[];
    use Blameable;
    public function transaksi(){
    	return $this->hasMany(Transaksi::class);
    }

    
}
