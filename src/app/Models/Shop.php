<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'area',
        'genre_id',
        'overview',
        'image',
        'user_id',
    ];

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    public function reservations()
    {
        return $this->belongsToMany(User::class, 'reservations')->withPivot('date', 'time_id', 'number_id', 'review', 'comment', 'comment_at','review_mail_sent')->withTimestamps();
    }
}
