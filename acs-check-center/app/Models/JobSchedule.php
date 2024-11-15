<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobSchedule extends Model
{
    use HasFactory;

    protected $table = 'job_schedules';

    protected $primaryKey = 'job_schedule_id';

    protected $fillable = [
        'job_authority_id',
        'job_schedule_date',
        'job_schedule_shift_id',
        'job_schedule_status_id',
    ];

    public function workShift()
    {
        return $this->belongsTo(WorkShift::class, 'job_schedule_shift_id', 'work_shift_id');
    }

    public function userAuthority()
    {
        return $this->belongsTo(UserAuthority::class, 'job_authority_id', 'user_authority_id');
    }
}
