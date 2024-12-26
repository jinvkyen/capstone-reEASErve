<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cms_reservation_duration extends Model
{
    use HasFactory;

    protected $table = 'cms_reservation_duration';

    protected $fillable = [
        'department',
        'cms_resource_type',
        'duration',
    ];
}
