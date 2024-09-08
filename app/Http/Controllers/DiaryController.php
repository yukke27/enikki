<?php

namespace App\Http\Controllers;

use App\Models\Diary;
use App\Models\Weather;
use App\Models\Tag;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Cloudinary;

class DiaryController extends Controller
{
    public function index(Diary $diary)
    {
        $tags = Tag::all();
        return view('diaries.index')->with([
            'diaries' => $diary->get(),
            'tags' => $tags,
        ]);
    }
    
    public function show(Diary $diary)
    {
        return view('diaries.show')->with(['diary' => $diary]);
    }
    
    public function create()
    {
        $weathers = Weather::all();//天気をすべて取得
        $tags = Tag::all();//タグをすべて取得
        
        return view('diaries.create')->with(['weathers' => $weathers, 'tags' => $tags]);
    }
    
    public function edit(Diary $diary)
    {
        $weathers = Weather::all();//天気をすべて取得
        $tags = Tag::all();//タグをすべて取得
        //日記に関連付けられたタグのIDを取得し配列に変換して$relatedTagIdsに格納
        $relatedTagIds = $diary->tags->pluck('id')->toArray();
        
        return view('diaries.edit')->with([
            'diary' => $diary,
            'weathers' => $weathers,
            'tags' => $tags,
            'relatedTagIds' => $relatedTagIds,
        ]);
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
        
        //テスト用に仮のcolor_idを入れる
        $diaryData += ['color_id' => 1];
        
        //取得したデータでPostモデルのインスタンスを更新しデータベースに保存
        $diary->fill($diaryData)->save();
        
        //リクエストデータから既存タグのIDの配列を取得
        $existingTagIds = $request['existing_tag_ids'];
        //$existingTagIdsが空でない場合、日記とタグを関連付ける
        if(!empty($existingTagIds)){
            $diary->tags()->sync($existingTagIds);
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
    
    public function update(Request $request, Diary $diary)
    {
        $diaryData = $request['diary'];
        $imageUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();
        $userId = auth()->id();
        $diaryData += ['image_url' => $imageUrl, 'user_id' => $userId];
        //テスト用に仮のcolor_idを入れる
        $diaryData += ['color_id' => 1];
        
        $diary->fill($diaryData)->save();
        
        $existingTagIds = $request['existing_tag_ids'];
        if(!empty($existingTagIds)){
            $diary->tags()->sync($existingTagIds);
        }
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
    
    public function gallery()
    {
        $userId = Auth::id();
        $favorites = Favorite::where('user_id', $userId)->pluck('diary_id')->toArray();
        
        return view('diaries.gallery')->with(['diaries' => Diary::all(), 'favorites' => $favorites]);
    }
    
    
}