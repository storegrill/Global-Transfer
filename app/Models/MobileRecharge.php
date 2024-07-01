<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobileRecharge extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone_number',
        'amount',
        'provider',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
