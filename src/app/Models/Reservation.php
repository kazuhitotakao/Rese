<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'shop_id',
        'date',
        'time',
        'number_id',
        'review',
        'comment',
        'comment_at',
        'review_mail_sent',
        'check_in',
        'check_in_at',
    ];

    protected $dates = [
        'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function number()
    {
        return $this->belongsTo(Number::class);
    }
}
