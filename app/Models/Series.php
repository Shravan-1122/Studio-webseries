<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Series extends Model
{
    protected $table = 'webseries'; 

    protected $primaryKey = 'id';

    public $incrementing = false; 

    protected $fillable = [
        'id',
        'title',
        'description',
        'theme_id',
        'status',
        'created_by',
        'updated_by',
        'active',
    ];
    public function artists()
    {
        return $this->belongsToMany(Artist::class, 'web_artist', 'web_id', 'artist_id');
    }

   public function theme()
    {
        return $this->belongsTo(Theme::class);
    }
   
}