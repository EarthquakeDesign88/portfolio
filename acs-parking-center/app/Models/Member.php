<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_code',
        'first_name',
        'last_name',
        'phone',
        'member_status',
        'member_type_id',
        'place_id',
        'company_id',
        'id_card',
        'license_driver',
        'license_plate',
        'issue_date',
        'expiry_date',
    ];

    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function memberType()
    {
        return $this->belongsTo(MemberType::class);
    }
}
