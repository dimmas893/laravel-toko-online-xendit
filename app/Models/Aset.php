<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Blameable;
class Aset extends Model
{
    use HasFactory;
    protected $table='aset';
    protected $guarded=[];
    use Blameable;

    
}
