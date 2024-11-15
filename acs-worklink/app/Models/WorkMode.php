<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkMode extends Model
{

    use HasFactory;

    protected $primaryKey = 'mode_id';

    protected $fillable = [
        'mode_desc_en',
        'mode_desc_th',
    ];

}
