<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cms extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $primaryKey = 'cms_id';

    protected $table = 'cms';

    protected $fillable = [
        'cms_id',
        'color',
        'dept_id',
        'emblem',
        'bg_image',
        'logo',
        'email',
        'edited_at',
        'edited_by'
    ];
}

