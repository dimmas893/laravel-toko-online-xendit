<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Blameable;
class Kategori extends Model
{
    use HasFactory;
    protected $table='kategori';
    protected $guarded=[];
    use SoftDeletes;
    use Blameable;
    public function produk(){
    	return $this->hasMany(Produk::class);
    }

    public function portofolio(){
    	return $this->hasMany(Portofolio::class);
    }
}
