<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class feedback extends Model
{
    use HasFactory;

    protected $primaryKey = 'feedback_id';

    protected $table = 'user_feedback';

    public $incrementing = false;

    protected $keyType = 'int';

    protected $fillable = [
        'feedback_id',
        'username',
        'feedback',
        'resource_id',
        'rating',
        'created_at',
        'updated_at'
    ];

    public function resource()
    {
        return $this->belongsTo(resources::class, 'resource_id');
    }

    protected $dates = ['created_at', 'updated_at'];

    public function getCreatedAtFormattedAttribute()
    {
        return $this->created_at->format('Y-m-d');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->feedback_id)) {
                $model->feedback_id = self::generateUniqueId();
            }
        });
    }

    public static function generateUniqueId()
    {
        // Generate a unique random integer
        do {
            $randomId = random_int(1, 9999999); // Adjust the range as needed
        } while (self::where('feedback_id', $randomId)->exists()); // Check for collisions

        return $randomId;
    }
}
