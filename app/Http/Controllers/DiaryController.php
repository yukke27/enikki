<?php

namespace App\Http\Controllers;

use App\Models\Diary;
use App\Models\Weather;
use App\Models\Template;
use App\Models\Tag;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\StoreDiaryRequest;
use App\Http\Requests\UpdateDiaryRequest;
use Cloudinary;
use Carbon\Carbon;

class DiaryController extends Controller
{
    public function index(Diary $diary)
    {
        $tags = Tag::all();
        $selectedYear = date('Y');
        $selectedMonth = date('n');
        
        $diaries = Diary::whereYear('date', $selectedYear)->whereMonth('date', $selectedMonth)->get();
        $datesWithPosts = $diaries->map(function ($diary) {
            return \Carbon\Carbon::parse($diary->date)->format('j');
        })->toArray();
        
        return view('diaries.index')->with([
            'selectedYear' => $selectedYear,
            'selectedMonth' => $selectedMonth,
            'datesWithPosts' => $datesWithPosts,
            'tags' => $tags,
        ]);
    }
    
    public function updateCalendar(Request $request)
    {
        $selectedYear = $request['year'];
        $selectedMonth = $request['month'];
        //選択された月の日記を取得
        $diaries = Diary::whereYear('date', $selectedYear)->whereMonth('date', $selectedMonth)->get();
        //日付を配列に変換
        //mapメソッド：対象のコレクションに引数のコールバック処理を実行する
        $datesWithPosts = $diaries->map(function ($diary) {
            return \Carbon\Carbon::parse($diary->date)->format('j');
        })->toArray();
        
        return response()->json(['html' => view('partials.calendar', compact('selectedYear', 'selectedMonth', 'datesWithPosts'))->render()]);
    }
    
    public function create()
    {
        $weathers = Weather::all();//天気をすべて取得
        $templates = Template::all();
        $tags = Tag::all();//タグをすべて取得
        
        //現在の年月日を取得
        $currentYear = date('Y');
        $currentMonth = date('m');
        $currentDate = date('d');
        //月の最終日を取得
        $daysInMonth = date('t');
        
        return view('diaries.create')->with([
            'weathers' => $weathers,
            'templates' => $templates,
            'tags' => $tags,
            'currentYear' => $currentYear,
            'currentMonth' => $currentMonth,
            'currentDate' => $currentDate,
            'daysInMonth' => $daysInMonth,
        ]);
    }
    
    public function edit(Diary $diary)
    {
        $weathers = Weather::all();//天気をすべて取得
        $templates = Template::all();
        $tags = Tag::all();//タグをすべて取得
        
        //現在の年を取得
        $currentYear = date('Y');
        //編集対象の日記から年月日を取得
        $date = Carbon::parse($diary->date);
        //日記に関連付けられたタグのIDを取得し配列に変換して$relatedTagIdsに格納
        $relatedTagIds = $diary->tags->pluck('id')->toArray();
        
        return view('diaries.edit')->with([
            'diary' => $diary,
            'weathers' => $weathers,
            'templates' => $templates,
            'tags' => $tags,
            'currentYear' => $currentYear,
            'selectedYear' => $date->year,
            'selectedMonth' => $date->month,
            'selectedDate' => $date->day,
            'daysInMonth' => $date->endOfMonth()->day,
            'selectedWeather' => $diary->weather_id,
            'relatedTagIds' => $relatedTagIds,
        ]);
    }
    
    public function store(StoreDiaryRequest $request, Diary $diary)
    {
        //dd($request->all());
        //リクエストからdiaryをキーに持つデータを取得し$diaryDataに代入
        $diaryData = $request['diary'];
        //YYYY-mm-ddに変換
        $date = $request['year'] . '-' . str_pad($request['month'], 2, '0', STR_PAD_LEFT) . '-' . str_pad($request['date'], 2, '0', STR_PAD_LEFT);
        //cloudinaryへ画像を送信し画像のURLを$image_urlに代入
        //fileメソッドはリクエストオブジェクトから送信されたファイルを取得するためのメソッド
        $imageUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();
        //userIdを取得
        $userId = auth()->id();
        $diaryData += ['user_id' => $userId, 'date' => $date, 'image_url' => $imageUrl];
        
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
        return redirect('/index');
    }
    
    public function update(UpdateDiaryRequest $request, Diary $diary)
    {
        $diaryData = $request['diary'];
        $date = $request['year'] . '-' . str_pad($request['month'], 2, '0', STR_PAD_LEFT) . '-' . str_pad($request['date'], 2, '0', STR_PAD_LEFT);
        if ($request->hasFile('image')){
            $imageUrl = Cloudinary::upload($request->file('image')->getRealPath())->getSecurePath();
        } else {
            $imageUrl = $diary->image_url;
        }
        $userId = auth()->id();
        $diaryData += ['user_id' => $userId, 'date' => $date, 'image_url' => $imageUrl];
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
        return redirect('/index');
    }
    
    public function gallery()
    {
        $userId = Auth::id();
        $favorites = Favorite::where('user_id', $userId)->pluck('diary_id')->toArray();
        
        return view('diaries.gallery')->with(['diaries' => Diary::orderBy('date', 'asc')->get(), 'favorites' => $favorites]);
    }
    
    
}