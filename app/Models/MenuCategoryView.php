<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuCategoryView extends Model
{
    use HasFactory;

    protected $fillable = ['menu_category_id', 'ip_address', 'user_agent', 'viewed_at'];

    public function menuCategory()
    {
        return $this->belongsTo(MenuCategory::class);
    }
}
