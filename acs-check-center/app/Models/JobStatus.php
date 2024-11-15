<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobStatus extends Model
{
    use HasFactory;

    protected $primaryKey = 'job_status_id';

    protected $fillable = [
        'job_status_description',
    ];

    
    public function jobSchedules()
    {
        return $this->belongsTo(JobSchedule::class, 'job_schedule_status_id', 'job_status_id');
    }

}
