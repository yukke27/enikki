<?php

namespace App\Http\Controllers;

use App\Models\Diary;
use App\Models\Tag;
use Illuminate\Http\Request;
use Cloudinary;

class DiaryController extends Controller
{
    public function index(Diary $diary)
    {
        return view('diaries.index')->with(['diaries' => $diary->get()]);
    }
    
    public function show(Diary $diary)
    {
        return view('diaries.show')->with(['diary' => $diary]);
    }
    
    public function create()
    {
        $tags = Tag::all();//タグをすべて取得
        return view('diaries.create')->with(['tags' => $tags]);
    }
    
    public function store(Request $request, Diary $diary)
    {
        //リクエストからdiaryをキーに持つデータを取得し$diaryDataに代入
        $diaryData = $request['diary'];
        //cloudinaryへ画像を送信し画像のURLを$image_urlに代入
        //fileメソッドはリクエストオブジェクトから送信されたファイルを取得するためのメソッド
        $imageUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();
        //userIdを取得
        $userId = auth()->id();
        $diaryData += ['image_url' => $imageUrl, 'user_id' => $userId];
        
        //テスト用にcolor_idにテキトーな数字を入れる
        $diaryData += ['color_id' => 1];
        
        //取得したデータでPostモデルのインスタンスを更新しデータベースに保存
        $diary->fill($diaryData)->save();
        
        //リクエストデータから既存タグのIDの配列を取得
        $existingTagIds = $request['existing_tag_ids'];
        //$existingTagIdsが空でない場合、日記とタグを関連付ける
        if(!empty($existingTagIds)){
            $diary->tags()->attach($existingTagIds);
        }
        //リクエストにnew_tagsが含まれている場合
        //new_tag_namesは配列
        if($request->has('new_tag_names')){
            foreach($request['new_tag_names'] as $newTagName){
                //新規タグを作成しデータベースに保存する
                //既に存在していた場合そのレコードを返す
                $newTagData = Tag::firstOrCreate(
                    ['name' => $newTagName],
                    ['user_id' => $userId]
                );
                $diary->tags()->attach($newTagData->id);
            }
        }
        
        return redirect('/diaries');
    }
}
