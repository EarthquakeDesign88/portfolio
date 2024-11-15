<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobQualification extends Model
{

    use HasFactory;

    protected $primaryKey = 'job_qualification_id';


    protected $fillable = [
        'company_en',
        'company_th',
        'work_place_en',
        'work_place_th',
        'working_day_en',
        'working_day_th',
        'day_off_en',
        'day_off_th',
        'working_time',
        'benefits_en',
        'benefits_th',
    ];

}
