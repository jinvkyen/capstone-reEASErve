<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class user_accounts extends Authenticatable
{
    use HasFactory;

    public $timestamps = false;

    protected $primaryKey = 'user_id';

    protected $table = 'user_accounts';

    public function userType()
    {
        return $this->belongsTo(userType::class, 'user_type', 'type_id');
    }

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'user_id',
        'password',
        'position',
        'dept_name',
        'profile_pic',
        'user_type'
    ];
}
