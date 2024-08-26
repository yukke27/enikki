<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Weather extends Model
{
    use HasFactory;
    
    //テーブル名を指定
    protected $table = 'weathers';
    
    public function diaries()
    {
        return $this->hasMany(Diary::class);
    }
}
