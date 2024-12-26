<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class archive_feedback extends Model
{
    use HasFactory;

    protected $primaryKey = 'feedback_id';

    protected $table = 'archive_feedbacks';

    protected $fillable = [
        'feedback_id',
        'username',
        'feedback',
        'resource_id',
        'rating',
        'created_at',
        'updated_at'
    ];
}
