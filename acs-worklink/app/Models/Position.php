<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{

    use HasFactory;

    protected $primaryKey = 'position_id';

   
    protected $fillable = [
        'position_desc_en',
        'position_desc_th',
        'position_status',
        'position_department_id',
        'position_job_qualification_id',
        'position_mode_id',
        'responsibilities_en',
        'responsibilities_th',
        'knowledge_skills_en',
        'knowledge_skills_th',
        'require_feature_en',
        'require_feature_th',
        'salary',
        'vacancies'
    ];
}
