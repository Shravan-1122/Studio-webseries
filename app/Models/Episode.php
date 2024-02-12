<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    protected $fillable = [
        'episode_title', 'description', 'season_id', 'created_by', 'updated_by', 'active',
    ];

    public function season()
    {
        return $this->belongsTo(Season::class);
    }
  
    public function artists()
    {
        return $this->belongsToMany(Artist::class, 'episode_artist', 'episode_id', 'artist_id');
    }
}