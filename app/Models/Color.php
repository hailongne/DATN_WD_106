<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Color extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'colors';
    protected $primaryKey = 'color_id';
    protected $fillable = ['name', 'color_code'];
    public function attributeProducts()
    {
        return $this->hasMany(AttributeProduct::class, 'color_id');
    }
    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'color_id');
    }
    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'attribute_products', 'color_id', 'size_id')
            ->withPivot('price', 'in_stock');  // Thêm các trường cần thiết từ bảng trung gian
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    
}
