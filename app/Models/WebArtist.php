<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WebArtist extends Model
{
    protected $table = 'web_artist';

    protected $fillable = [
        'web_id', 'artist_id'
    ];

   
    public function artists()
    {
        return $this->belongsToMany(WebArtist::class, 'web_artist', 'web_id', 'artist_id');
    }
}