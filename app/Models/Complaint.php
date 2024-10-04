<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'reported_user_id', 'reason', 'details'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function reportedUser() {
        return $this->belongsTo(User::class, 'reported_user_id');
    }
}
