<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormCreate extends Model
{
    use HasFactory;

    protected $primaryKey = 'form_id';
    
    protected $table = 'form_create';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'mobile',
        'salary',
        'cv',
        'portfolio',
        'mail_status'
    ];
}
