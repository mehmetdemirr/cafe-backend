<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'message',
        'business_id',
    ];

    // İlişkiler
    public function cafe()
    {
        return $this->belongsTo(Business::class);
    }
}
