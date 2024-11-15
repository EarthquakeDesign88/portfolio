<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkShift extends Model
{
    use HasFactory;

    protected $table = 'work_shifts';

    protected $primaryKey = 'work_shift_id';

    protected $fillable = [
        'work_shift_description',
        'work_shift_slottime',
    ];

    public function jobSchedules()
    {
        return $this->hasMany(JobSchedule::class, 'job_schedule_shift_id', 'work_shift_id');
    }
}
