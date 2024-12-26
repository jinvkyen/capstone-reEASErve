<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class System_Admin_Account extends Authenticatable
{
    use HasFactory;

    protected $table = 'system_admin_accounts';

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'created_at',
        'updated_at',
        'status'
    ];
}
