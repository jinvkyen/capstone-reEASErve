<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Facility extends Model
{
    use HasFactory;
    protected $primaryKey = 'facilities_id';
    public $timestamps = false;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'facilities_id',
        'facility_name',
        'location',
        'department_owner',
        'image',
        'policy',
        'status',
        'is_available',
        'added_by',
        'edited_by',
        'edited_at',
        'created_at',
        'deleted_at'
    ];

    public function reservations()
    {
        return $this->hasMany(Facility_Reservation::class, 'facility_name');
    }

    public function getIsAvailableAttribute()
    {
        $currentDateTime = Carbon::now();
        return $this->reservations()
            ->where('start_datetime', '<=', $currentDateTime)
            ->where('end_datetime', '>=', $currentDateTime)
            ->doesntExist();
    }
}
