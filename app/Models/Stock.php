<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
class Stock extends Model
{
    use HasFactory;
    protected $table='stock';
    protected $guarded=[];
    use SoftDeletes;
    use Blameable;
    public function produk(){
    	return $this->belongsTo(Produk::class);
    }

}
