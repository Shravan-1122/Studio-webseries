<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeasonArtist extends Model
{

    protected $table = 'seasonartist';
    protected $fillable = [
        'season_id', 'artist_id'
    ];

    public function webSeries()
    {
        return $this->belongsTo(Series::class, 'web_id');
    }
}