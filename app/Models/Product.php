<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $primaryKey = 'product_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'stock_quantity',
        'image_url',
    ];

    protected $casts = [
        'price'          => 'float',
        'stock_quantity' => 'int',
    ];

    // ใช้ product_id สำหรับ implicit route model binding เช่น /order/add/{product}
    public function getRouteKeyName()
    {
        return 'product_id';
    }

    /* -------------------- Relationships -------------------- */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'product_id', 'product_id');
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class, 'product_id', 'product_id');
    }

    public function usersWhoWished()
    {
        return $this->belongsToMany(User::class, 'wishlists', 'product_id', 'user_id')
                    ->withPivot('wishlist_date')
                    ->withTimestamps();
    }
}
