<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'business_id',
    ];

    // İlişkiler
    public function cafe()
    {
        return $this->belongsTo(related: Business::class);
    }

    public function attendees()
    {
        return $this->belongsToMany(User::class, 'event_user');
    }
}
