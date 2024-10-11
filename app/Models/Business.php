<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'address',
        'qr_code',
        'phone_number',         // Telefon numarası
        'website_url',          // Web sitesi adresi
        'description',          // İşletme açıklaması
        'location_latitude',    // Konum (enlem)
        'location_longitude',   // Konum (boylam)
        'image_url',            // İşletme resmi
        'opening_time',         // Açılış saati
        'closing_time',         // Kapanış saati
    ];

    // İlişkiler
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function menuCategories()
    {
        return $this->hasMany(MenuCategory::class);
    }

    public function campaigns()
    {
        return $this->hasMany(Campaign::class);
    }

    public function events()
    {
        return $this->hasMany(Event::class);
    }

    public function ratings()
    {
        return $this->hasMany(BusinessRating::class);
    }

    public function favoriteByUsers()
    {
        return $this->belongsToMany(User::class, 'user_favorite_businesses');
    }

    // Değerlendirme sayısını almak için
    public function ratingCount()
    {
        return $this->ratings()->count(); // Değerlendirme sayısını döndürür
    }

}
