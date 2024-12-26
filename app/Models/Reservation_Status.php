<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation_Status extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'status_id';

    protected $table = 'reservation_status';

    protected $fillable = [
        'status_id',
        'status_state',
        'edited_at',
        'edited_by'
    ];
}
