<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponUser extends Model
{
    use HasFactory;
    protected $table='coupon_user';
    protected $primaryKey='coupon_user_id';
    protected $fillable=[
        'coupon_id','user_id'
    ];
    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id'); // Sử dụng đúng tên khóa ngoại
    }
}
