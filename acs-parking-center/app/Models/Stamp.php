<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stamp extends Model
{
    use HasFactory;

    // protected $table = 'stamp';

    protected $fillable = [
        'stamp_code',
        'stamp_condition',
    ];
}
