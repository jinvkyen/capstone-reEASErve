<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_reservation_requests extends Model
{
    use HasFactory;

    protected $primaryKey = 'transaction_id';

    protected $table = 'user_reservation_requests';

    protected $fillable = [
        'transaction_id',
        'user_id',
        'resource_id',
        'resource_type',
        'pickup_datetime',
        'return_datetime',
        'serial_number',
        'professor',

        'subject',
        'section',
        'schedule',
        'group_members',

        'purpose',
        'status',

        'noted_by',

        'approved_by',
        'returned_to',

        'created_at',
        'remarks',
        'released_by',
        'deleted_at',
    ];
}
