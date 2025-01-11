<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reviews extends Model
{
    use HasFactory;
    protected $table='reviews';
    protected $primaryKey='review_id';
    protected $fillable = ['product_id', 'user_id', 'rating', 'title', 'comment','image'];

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function replies()
{
    return $this->hasMany(ReviewsReply::class, 'review_id');
}
public function likes(){
    return $this->hasMany(Like::class,'review_id');
}
public function reports(){
    return $this->hasMany(Report::class,'review_id');
}


}
