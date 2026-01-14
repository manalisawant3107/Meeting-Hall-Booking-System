<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hall extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'available_days' => 'array',
    ];

    public function images()
    {
        return $this->hasMany(HallImage::class);
    }
}
