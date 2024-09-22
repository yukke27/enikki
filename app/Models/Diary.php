<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diary extends Model
{
    use HasFactory;
    
    protected $fillable = [
            'user_id',
            'date',
            'weather_id',
            'color_id',
            'template_id',
            'title',
            'body',
            'image_url',
        ];
    
    public function weather()
    {
        return $this->belongsTo(Weather::class);
    }
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
