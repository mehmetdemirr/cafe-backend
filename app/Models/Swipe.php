<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Swipe extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'swiped_user_id', 'is_right'];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function swipedUser()
    {
        return $this->belongsTo(User::class, 'swiped_user_id');
    }

    public function matchup()
    {
        return $this->hasOne(Matchup::class, 'user_one_id', 'user_id')
            ->where('user_two_id', $this->swiped_user_id);
    }
}
