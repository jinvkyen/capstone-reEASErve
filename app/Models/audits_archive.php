<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class audits_archive extends Model
{
    use HasFactory;

    protected $table = 'audits_archive';

    protected $fillable = [
        'id',
        'action',
        'made_by',
        'user_id',
        'action_type',
        'datetime',
        'departments'
    ];
}
