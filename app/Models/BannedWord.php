<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BannedWord extends Model
{
    use HasFactory;
    protected $table = 'banned_words';

    protected $primaryKey='banned_word_id';
    protected $fillable = ['word'];
}
