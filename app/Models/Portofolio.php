<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Portofolio extends Model
{
    use HasFactory;
    protected $table='portofolio';
    protected $guarded=[];
    use SoftDeletes;
    use Blameable;
    public function kategori(){
    	return $this->belongsTo(Kategori::class);
    }
}
