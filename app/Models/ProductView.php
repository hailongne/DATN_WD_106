<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductView extends Model
{
    use HasFactory;
    protected $table = 'product_views';
    protected $primaryKey = 'product_view_id';
    protected $fillable = [
        'product_id',
        'user_id',
        'view_count',
    ];
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }
}
