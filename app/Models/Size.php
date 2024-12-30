<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Size extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'sizes';
    protected $primaryKey = 'size_id';
    protected $fillable = ['name'];
    public function attributeProducts()
    {
        return $this->hasMany(AttributeProduct::class, 'size_id');
    }
    public function colors()
    {
        return $this->belongsToMany(Color::class, 'attribute_products', 'size_id', 'color_id')
            ->withPivot('price', 'in_stock');  // Lấy các trường từ bảng trung gian
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
