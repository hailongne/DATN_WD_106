<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReviewsReply extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'review_replies';
protected $primaryKey = 'review_replie_id';
    protected $fillable = [
        'review_id',
        'user_id', // ID của quản trị viên
        'content',
        'product_id',
    ];

    /**
     * Mối quan hệ một-nhiều với bình luận (review).
     */
    public function review()
    {
        return $this->belongsTo(Reviews::class, 'review_id');
    }

    /**
     * Mối quan hệ một-nhiều với quản trị viên (user).
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
