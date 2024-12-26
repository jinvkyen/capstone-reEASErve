<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class general_policies extends Model
{
    use HasFactory;

    protected $table = 'general_policies';

    protected $fillable = [
        'policy_name',
        'policy_content',
        'dept_owner',
        'added_by',
        'edited_by',
        'updated_at'
    ];
}
