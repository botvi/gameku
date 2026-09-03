<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price_kp',
        'image_path',
        'thumbnail_path',
        'filename',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price_kp'  => 'integer',
    ];

    /**
     * Users who have purchased this item.
     */
    public function purchasedByUsers()
    {
        return $this->belongsToMany(User::class, 'user_shop_items', 'shop_item_id', 'user_id')
                    ->withTimestamps();
    }
}
