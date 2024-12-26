<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class audit extends Model
{
    use HasFactory;

    public $incrementing = false;

    public $timestamps = false;

    protected $keyType = 'int';

    protected $fillable = [
        'id',
        'action',
        'made_by',
        'user_id',
        'user_type',
        'action_type',
        'datetime',
        'department'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = self::generateUniqueId();
            }
        });
    }

    public static function generateUniqueId()
    {
        // Generate a unique random integer
        do {
            $randomId = random_int(1, 9999999); // Adjust the range as needed
        } while (self::where('id', $randomId)->exists()); // Check for collisions

        return $randomId;
    }
}
