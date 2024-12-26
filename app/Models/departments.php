<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class departments extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $primaryKey = 'department_id';

    protected $table = 'departments';

    protected $fillable = [
        'department_id',
        'department_name',
        'college',
        'edited_at',
        'edited_by'
    ];
}
