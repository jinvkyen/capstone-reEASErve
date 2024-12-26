<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerifyToken extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'verify_tokens';

    protected $fillable = [
        'token',
        'email',
        'is_activated'
    ];
}
