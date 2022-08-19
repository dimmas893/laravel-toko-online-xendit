<?php

namespace App\Models;

use App\Blameable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Pengeluaran extends Model
{
    use HasFactory;
    protected $table='pengeluaran';
    protected $guarded=[];
    use SoftDeletes;
    use Blameable;

}
