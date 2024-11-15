<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repair extends Model
{
    use HasFactory;

    
    protected $primaryKey = 'repair_id';

    protected $fillable = [
        'job_id',
        'repair_status'
    ];
}
