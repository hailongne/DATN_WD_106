<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttributeProduct extends Model
{
    use HasFactory;
    protected $table = 'attribute_products';
    protected $primaryKey = 'attribute_product_id';
    protected $fillable = ['product_id', 'color_id', 'size_id', 'in_stock', 'price','warning_threshold'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id');
    }
    // Mối quan hệ với bảng Size
    public function size()
    {
        return $this->belongsTo(Size::class, 'size_id');

    }
   
}