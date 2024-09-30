<?php

namespace App\Http\Controllers;

use App\Models\Diary;
use App\Models\Tag;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function index(Request $request)
    {
        $tagIds = $request->input('tag_ids');
        
        /*
        whereHasメソッドを使うと
        Diaryモデルが持つリレーション（tags）に対して条件が設定でき、
        この条件に一致するTagモデルが関連付けられているDiaryモデルだけを取得することができる
        
        第一引数でリレーション名を指定
        第二引数でリレーションに関する条件を指定するための匿名関数を指定
        第三引数（オプション）でリレーションの件数に対する比較演算子を指定（指定しない場合'>'）
        第四引数で比較する値を指定
        
        第一引数：
        tagsリレーションを指定
        
        第二引数：
        匿名関数の中でtagsリレーションに対して以下のフィルタリング条件を設定
        「日記に関連付けられたタグのIDが、選択されたタグのIDのいずれかに一致する」
        
        第三引数：
        リレーションの件数に対する比較演算子'='を指定
        ここでいうリレーションの件数とは
        「Diaryモデルに関連付けられているTagモデルの内、第二引数で指定した条件に一致するTagモデルの数」
        
        第四引数：
        検索の際に選択されたタグの数を指定
        「この日記に関連付けられたタグの内、選択されたタグのIDと一致するタグの数」と
        「検索の際に選択されたタグの数」を比較する
        */
        /*
        匿名関数の引数として渡している'$q'はクエリビルダーインスタンスというらしい
        クエリビルダーインスタンスが何なのか何故必要なのか調べておく
        */
        $userId = Auth::id();
        $diaries = Diary::whereHas('tags', function($q) use ($tagIds) {
            $q->whereIn('tags.id', $tagIds); //日記に関連付けられたタグのIDが$tagIdsのいずれかと一致するか
        }, '=', count($tagIds))->orderBy('date', 'asc')->get();
        $tags = Tag::whereIn('id', $tagIds)->get();
        $favorites = Favorite::where('user_id', $userId)->pluck('diary_id')->toArray();
        
        return view('tags.index')->with([
            'diaries' => $diaries,
            'favorites' => $favorites,
            'tags' => $tags
        ]);
    }
}