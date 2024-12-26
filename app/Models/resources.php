<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class resources extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $primaryKey = 'resource_id';
    public $timestamps = false;

    protected $fillable = [
        'resource_id',
        'resource_name',
        'resource_type',
        'serial_number',
        'image',
        'rating',
        'status',
        'department_owner',
        'policy_id',
        'created_at',
        'edited_at',
        'edited_by',
        'added_by',
        'deleted_at'
    ];


    public function feedbacks()
    {
        return $this->hasMany(feedback::class, 'resource_id');
    }
}
