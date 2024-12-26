<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cms_about extends Model
{
    use HasFactory;

    protected $table = 'cms_about';

    protected $fillable = [
        'header',
        'content',
        'department'
    ];
}
