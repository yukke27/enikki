<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Favorite;
use App\Models\Diary;

class FavoriteController extends Controller
{
    public function store(Request $request)
    {
        $userId = Auth::id();
        $diaryId = $request->input('diary_id');
        $isFavorite = $request->input('favorite');
        
        if ($isFavorite) {
            //念のため既に存在していないかを確認してから作成する
            Favorite::firstOrCreate(['user_id' => $userId, 'diary_id' => $diaryId]);
        } else {
            Favorite::where('user_id', $userId)
                ->where('diary_id', $diaryId)
                ->delete();
        }
        
        return response()->json(['success' => true]);
    }
}
