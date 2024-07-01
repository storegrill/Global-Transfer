<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    // Optional: Define the table name explicitly if needed
    // protected $table = 'accounts';

    // Optional: Define fillable attributes to mass assignment
    // protected $fillable = ['user_id', 'account_number', 'balance'];

    // Optional: Define guarded attributes to prevent mass assignment
    // protected $guarded = [];

    // Optional: Define relationships like belongsTo, hasMany, etc.
    // Example: Relationship to User model
    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }
}
