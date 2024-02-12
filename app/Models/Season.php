<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    protected $fillable = [
        'season_title', 'description', 'web_id', 'created_by', 'updated_by', 'active',
    ];

    public function webSeries()
    {
        return $this->belongsTo(Series::class, 'web_id');
    }
    public function theme()
    {
        return $this->belongsTo(Theme::class, 'theme_id');
    }
    public function artists()
    {
        return $this->belongsToMany(Artist::class, 'seasonartist', 'season_id', 'artist_id');
    }
}