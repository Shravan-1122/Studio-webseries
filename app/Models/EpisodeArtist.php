<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EpisodeArtist extends Model
{
    protected $table = 'episode_artist';

    protected $fillable = [
        'episode_id', 'artist_id'
    ];
}