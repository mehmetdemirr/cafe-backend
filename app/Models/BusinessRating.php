<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessRating extends Model
{
    use HasFactory;


    protected $fillable = [
        'rating',
        'comment',
        'user_id',
        'business_id',
    ];

    // İlişkiler
    public function cafe()
    {
        return $this->belongsTo(related: Business::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
