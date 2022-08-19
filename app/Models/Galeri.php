<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Blameable;
class Galeri extends Model
{
    use HasFactory;
    protected $table='gambar';
    protected $guarded=[];
  
    use Blameable;
   
}
