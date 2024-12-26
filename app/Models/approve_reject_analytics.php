<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class approve_reject_analytics extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'status',
        'changed_at'
    ];
}
