<?php

namespace App\Http\Controllers;

use App\Models\Diary;
use App\Models\Tag;
use Illuminate\Http\Request;
use Cloudinary;

class DiaryController extends Controller
{
    public function index()
    {
        return view('diaries.index');
    }
    
    public function show()
    {
        //テスト用にidが1の日記をビューに渡す
        $diary = Diary::with(['weather', 'tags'])->find(5);
        return view('diaries.show')->with(['diary' => $diary]);
    }
    
    public function create()
    {
        $tags = Tag::all();//タグをすべて取得
        return view('diaries.create')->with(['tags' => $tags]);
    }
    
    public function store(Request $request, Diary $diary)
    {
        //リクエストからdiaryをキーに持つデータを取得し$inputDiaryに代入
        $inputDiary = $request['diary'];
        //cloudinaryへ画像を送信し画像のURLを$image_urlに代入
        //fileメソッドはリクエストオブジェクトから送信されたファイルを取得するためのメソッド
        $image_url = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();
        //user_idを取得
        $user_id = auth()->id();
        $inputDiary += ['image_url' => $image_url, 'user_id' => $user_id];
        
        //テスト用にcolor_idにテキトーな数字を入れる
        $inputDiary += ['color_id' => 1];
        
        //取得したデータでPostモデルのインスタンスを更新しデータベースに保存
        $diary->fill($inputDiary)->save();
        
        //リクエストデータからタグのIDの配列を取得
        $inputTags = $request['tags'];
        //$inputTagsが空でない場合、日記とタグを関連付ける
        if(!empty($inputTags)){
            $diary->tags()->sync($inputTags);
        }
        
        return redirect('/diaries');
    }
}
