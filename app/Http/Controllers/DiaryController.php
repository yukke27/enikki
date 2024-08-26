<?php

namespace App\Http\Controllers;

use App\Models\Diary;
use Illuminate\Http\Request;

class DiaryController extends Controller
{
    public function index()
    {
        return view('diaries.index');
    }
    
    public function show()
    {
        $diary = Diary::with(['weather', 'tags'])->find(1);
        return view('diaries.show')->with(['diary' => $diary]);
    }
}
