<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone_number',
        'biography',
        'country',
        'city',
        'district',
        'is_premium',
        'gender',
        'profile_picture',
    ];

    // İlişki tanımı
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
