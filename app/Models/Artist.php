<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    protected $fillable = [
        'name', 'age'
    ];

    public function episodes()
{
    return $this->belongsToMany(Episode::class, 'episode_artist', 'artist_id', 'episode_id');
}
}