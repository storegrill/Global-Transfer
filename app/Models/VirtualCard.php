<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VirtualCard extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'card_number',
        'expiry_date',
        'cvv',
    ];

    /**
     * Get the user that owns the virtual card.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
