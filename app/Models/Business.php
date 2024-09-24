<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'qr_code',
        'user_id',
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

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function ratings()
    {
        return $this->hasMany(CafeRating::class);
    }

    public function favoriteByUsers()
    {
        return $this->belongsToMany(User::class, 'user_favorite_businesses');
    }

}
