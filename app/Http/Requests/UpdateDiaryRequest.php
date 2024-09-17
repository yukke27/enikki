<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDiaryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'diary.weather_id' => 'required',
            'diary.title' => 'required|string|max:255',
            'diary.body' => 'required|string|max:255',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:255',
        ];
    }
    
    public function withValidator(Validator $validator)
    {
        $validator->after(function ($validator){
            $year = str_pad($this->year, 4, '0', STR_PAD_LEFT);
            $month = str_pad($this->month, 2, '0', STR_PAD_LEFT);
            $date = str_pad($this->date, 2, '0', STR_PAD_LEFT);
           
            $userId = auth()->id();
            $selectedDate = "{$year}-{$month}-{$date}";
            
            $exists = Diary::where('user_id', $userId)
                ->whereDate('created_at', $selectedDate)
                ->exists();//存在する場合trueを返す
                
            if ($exists) {
                $validator->errors()->add('date', '選択された日付の日記がすでに存在します');
            }
        });
    }
}
