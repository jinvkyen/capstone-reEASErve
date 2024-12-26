<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility_Reservation extends Model
{
    use HasFactory;

    protected $table = 'facility_reservation';

    protected $fillable = [
        'id',
        'facility_name',
        'user_id',
        'purpose',
        'status',
        'start_datetime',
        'end_datetime',
        'created_at',
        'remarks',
        'deleted_at',
        'approved_by',
        'released_by'
    ];

    public function facility()
    {
        return $this->belongsTo(Facility::class, 'facility_id');
    }
}
