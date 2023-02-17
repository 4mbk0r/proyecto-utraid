<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dar_cita extends Model
{

    use HasFactory;
    protected $fillable = [
        'id_ficha',
        'id_persona'
    ];
}
