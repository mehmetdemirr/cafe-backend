<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'media_path',
        'sender_id',
        'receiver_id',
        'match_id',
    ];

    // İlişkiler
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function match()
    {
        return $this->belongsTo(Matchup::class, 'match_id'); // Match modeli ile ilişki
    }
}
