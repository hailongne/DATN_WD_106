<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsedCoupon extends Model
{
    use HasFactory;
    protected $table = 'used_coupons';
    protected $primaryKey = 'used_coupon_id';
    protected $fillable = ['user_id','coupon_id'];

}
