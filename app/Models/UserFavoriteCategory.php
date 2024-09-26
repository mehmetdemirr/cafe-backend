<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserFavoriteCategory extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'category_id'];

    // User ilişkilendirme
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Category ilişkilendirme
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
