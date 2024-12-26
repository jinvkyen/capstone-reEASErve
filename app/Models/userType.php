<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class userType extends Model
{
    use HasFactory;

    protected $primaryKey = 'type_id';

    protected $table = 'user_type';
}
