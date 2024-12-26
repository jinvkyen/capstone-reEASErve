<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cms_status_colors extends Model
{
    use HasFactory;

    protected $fillable = [
        'status_color',
        'created_at',
        'updated_at'
    ];
}
