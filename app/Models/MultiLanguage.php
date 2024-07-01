<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MultiLanguage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'language',
    ];

    // Define relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
