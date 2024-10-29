<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'start_date',
        'end_date',
        'business_id',
        'quota',
        'is_paid',
        'views',
        'category',
        'image_url',
        'location',               // İşletme dışındaki etkinlikler için yer bilgisi
        'price',                  // Etkinlik ücreti (varsa)
        'registration_deadline',  // Kayıt son tarihi (nullable)
        'is_offsite',             // İşletme dışında mı yapılacak? (true/false)
    ];

    // İlişkiler
    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function attendees()
    {
        return $this->belongsToMany(User::class, 'event_user');
    }
}
