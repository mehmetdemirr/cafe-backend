<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'price',
        'description',
        'menu_category_id',
        'business_id',       
        'views',           
        'is_available',  
        'additional_info',   // Ek bilgi
        'calories',        
    ];

    // İlişkiler
    public function category()
    {
        return $this->belongsTo(MenuCategory::class);
    }

    public function images()
    {
        return $this->hasMany(MenuItemImage::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
