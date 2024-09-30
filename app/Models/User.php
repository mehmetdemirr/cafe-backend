<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable , HasRoles , HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_picture', 
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // İlişkiler
    public function business()
    {
        return $this->hasOne(Business::class);
    }
    public function favoriteBusinesses()
    {
        return $this->belongsToMany(Business::class, 'user_favorite_cafes');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function attendedEvents()
    {
        return $this->belongsToMany(Event::class, 'user_events');
    }

    public function loyaltyPoints()
    {
        return $this->hasOne(LoyaltyPoint::class);
    }

    public function favoriteCategories()
    {
        return $this->belongsToMany(Category::class, 'user_favorite_categories');
    }

    public function campaigns()
    {
        return $this->belongsToMany(Campaign::class, 'campaign_user')->withTimestamps();
    }
    
}
