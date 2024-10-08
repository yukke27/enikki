<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id', 'name'];
    
    public function diaries()
    {
        return $this->belongsToMany(Diary::class);
    }
}
