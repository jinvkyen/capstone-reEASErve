<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class registration_policy extends Model
{
    use HasFactory;

    protected $table = 'registration_policy';

    protected $fillable = [
        'title',
        'content',
        'created_at',
    ];
}
