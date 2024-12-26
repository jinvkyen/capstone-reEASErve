<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource_Type extends Model
{
    use HasFactory;
    protected $table = 'resource_type';
    protected $primaryKey = 'category_id';
    public $timestamps = false;

    protected $fillable = [
        'category_id',
        'resource_type',
        'edited_at',
        'edited_by'
    ];
}
