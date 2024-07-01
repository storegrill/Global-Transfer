<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'body',
        'is_read',
    ];

    /**
     * The user that owns the notification.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
