<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Max extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'time10',
        'time11',
        'time12',
        'time13',
        'time14',
        'time15',
        'time16',
        'time17',
        'time18',
        'time19',
        'time20',
        'time21',
        'time22',
        'time23',
    ];

    // Userモデルとのリレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
