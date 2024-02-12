<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    protected $fillable = [
        'title',
        'description',
    ];
    public function theme()
    {
        return $this->belongsTo(Theme::class, 'theme_id');
    }
}
