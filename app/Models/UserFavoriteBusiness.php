<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFavoriteBusiness extends Model
{
    use HasFactory;

    protected $table = 'user_favorite_businesses'; // Tablo adı

    protected $fillable = [
        'user_id',
        'business_id',
    ];

    // İlişkiler
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
