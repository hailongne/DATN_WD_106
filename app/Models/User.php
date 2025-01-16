<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'address',
        'birth_date',
        'gender',
        'profile_image',
        'phone',
        'is_active',
        'password',
        'remember_token',
        'role'
    ];

 
    protected $primaryKey = 'user_id';
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function coupons()
    {
        return $this->belongsToMany(Coupon::class, 'coupon_user', 'user_id', 'coupon_id');
    }
    public function shoppingCart()
    {
        return $this->hasOne(ShoppingCart::class, 'user_id');
    }
    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }
    public function employee()
    {
        return $this->hasOne(Employee::class, 'user_id', 'user_id');
    }

    public function customer()
    {
        return $this->hasOne(Customer::class, 'user_id', 'user_id');
    }
    public function reviews(){
   
        return $this->hasMany(Reviews::class, 'user_id', 'user_id');}
        public function usedCoupons()
        {
            return $this->belongsToMany(Coupon::class, 'used_coupons')->withTimestamps();
        }
    }

