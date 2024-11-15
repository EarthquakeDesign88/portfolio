<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAuthority extends Model
{
    use HasFactory;

    protected $table = 'user_authorities';

    protected $primaryKey = 'user_authority_id';

    protected $fillable = [
        'user_id',
        'user_location_id',
    ];

    public function jobSchedules()
    {
        return $this->hasMany(JobSchedule::class, 'job_authority_id', 'user_authority_id');
    }
}
