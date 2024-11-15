<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanySetup extends Model
{
    use HasFactory;

    protected $table = 'company_setup';

    protected $fillable = [
        'company_id',
        'stamp_id',
        'place_id',
        'floor_id',
        'total_quota',
        'remaining_quota'
    ];
}
