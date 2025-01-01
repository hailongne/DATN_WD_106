<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $primaryKey = 'order_id';
    protected $fillable = [
        'user_id',
        'order_date',
        'status',
        'total',
        'invoice_date',
        'payment_status',
        'shipping_address',
        'phone',
        'payment_method',
        'create_at',
        'update_at',
        'recipient_name',
        'received'
        // Thêm các thuộc tính khác nếu cần
    ];
    protected $casts = [
        'order_date' => 'datetime', // Chuyển đổi cột order_date thành Carbon
    ];
    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id', 'order_id');
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
    public function products()
    {
        return $this->belongsToMany(Product::class, 'order_items', 'order_id', 'product_id');
    }
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');  // Đảm bảo mối quan hệ sử dụng đúng trường order_id
    }
    public function statusHistories()
    {
        return $this->hasMany(OrderStatusHistory::class, 'order_id');
    }
}
