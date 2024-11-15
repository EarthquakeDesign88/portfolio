<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IssueTopic extends Model
{
    use HasFactory;
    protected $primaryKey = 'issue_id';

    protected $fillable = [
        'issue_description',
        'supervisor_id',
    ];

    
    public function jobSchedules()
    {
        return $this->belongsTo(JobSchedule::class, 'job_schedule_issue_id', 'issue_id');
    }
}
