<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoveProduct extends Model
{
    use HasFactory;
    protected $table='love_product';
    protected $primaryKey='love_product_id';
    protected $fillable = ['user_id', 'product_id'];
}
