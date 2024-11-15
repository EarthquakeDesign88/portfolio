<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParkingRecord extends Model
{
    use HasFactory;

    protected $table = 'parking_records';
    public $timestamps = false;

    protected $fillable = [
        'parking_pass_code',
        'parking_pass_type',
        'license_plate',
        'license_plate_path',
        'stamp_id',
        'stamp_qty',
        'carin_datetime',
        'carout_datetime',
        'added_manually',
    ];
}
