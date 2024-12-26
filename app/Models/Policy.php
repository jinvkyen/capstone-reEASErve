<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Policy extends Model
{
    use HasFactory;
    protected $primaryKey = 'policy_id';

    protected $fillable = [
        'policy_id',
        'policy_name',
        'policy_content',
        'inclusions',
        'department_owner',
        'created_at',
        'updated_at'
    ];
}
