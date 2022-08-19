<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Testimoni extends Model
{
    use HasFactory;
    protected $table='testimoni';
    protected $guarded=[];
    use SoftDeletes;
    use Blameable;
  
}
