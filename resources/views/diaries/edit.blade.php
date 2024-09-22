<!DOCTYPE HTML>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @vite('resources/css/app.css')
        <title>Edit</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:ital,wght@0,100..700;1,100..700&display=swap" rel="stylesheet">
    </head>
    <body style="font-family: 'Roboto Mono">
        <div class="flex m-20 justify-between items-center">
            <a href="/index" class="flex w-64 h-auto">
                <img src="/images/icons/logo.svg" alt="logo">
            </a>
            <div class="flex space-x-4">
                <a href="/diaries/create" class="w-12 h-12 rounded-full bg-white border border-black flex justify-center items-center">
                    <img src="/images/icons/add.svg" alt="new diary" class="w-8 h-8 object-cover">
                </a>
                <a href="/index" class="w-12 h-12 rounded-full bg-white border border-black flex justify-center items-center">
                    <img src="/images/icons/home.svg" alt="home" class="w-8 h-8 object-cover">
                </a>
                <a href="" class="w-12 h-12 rounded-full bg-white border border-black flex justify-center items-center">
                    <img src="/images/icons/menu.svg" alt="menu" class="w-8 h-8 object-cover">
                </a>
            </div>
        </div>
        <!-- /diariesにPOSTメソッドでデータが送信される -->
        <!-- enctype属性はファイルを送信する際のデータ形式を決める -->
        <form action="/diaries/{{ $diary->id }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method("PUT")
            <div class="date">
                <select name="year" id="year">
                    <!-- PHPのdate関数を使ってページを開いた時点での年を取得 -->
                    @for ($i = 2020; $i <= $currentYear; $i++)
                        <!-- $iが$currentYearと等しいときselectedを返し一致しない場合は空文字列を返す -->
                        <option value="{{ $i }}" {{ old('year', $selectedYear) == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
                <select name="month" id="month">
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ old('month', $selectedMonth) == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
                <select name="date" id="date">
                    @for ($i = 1; $i <= $daysInMonth; $i++)
                        <option value="{{ $i }}" {{ old('date', $selectedDate) == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
                <p id="weekday"></p>
            </div>
            <div class="weather">
                <div class="flex">
                    @foreach($weathers as $weather)
                        <label class="m-4 cursor-pointer" for="weather-{{ $weather->id }}">
                            <input class="hidden peer" type="radio" name="diary[weather_id]" id="weather-{{ $weather->id }}" value="{{ $weather->id }}"
                                {{ old('diary.weather_id') == $weather->id ? 'checked' : ''}}>
                            <img class="w-16 h-16 opacity-25 peer-checked:opacity-100" src="{{ asset('images/weathers/' . $weather->name . '.svg') }}">
                        </label>
                    @endforeach
                </div>
                <p class="weather_error" style="color:red">{{ $errors->first('diary.weather_id') }}</p>
            </div>
            <div class="title">
                <p>Title.</p>
                <!-- name属性はサーバー側で扱うときのキーになる -->
                <input type="text" name="diary[title]" value="{{ old('diary.title', $diary->title) }}"/>
                <p class="title_error" style="color:red">{{ $errors->first('diary.title') }}</p>
            </div>
            <div class="body">
                <p>Body.</p>
                <textarea name="diary[body]">{{ old('diary.body', $diary->body) }}</textarea>
                <p class="body_error" style="color:red">{{ $errors->first('diary.body') }}</p>
            </div>
            <!--
            <p>古い入力：{{ old('diary.body') }}</p>
            <p>編集前：{{ $diary->body }}</p>
            -->
            <!-- tagsテーブルの情報をチェックボックスで表示 -->
            <!-- inputタグでタグの新規作成 -->
            <div class="tags">
                <p>Tags.</p>
                @foreach($tags as $tag)
                    <label for="tag-{{ $tag->id }}">{{ $tag->name }}</label>
                    <!-- 
                        ループ内で処理されているタグが編集対象の日記に関連付けられているタグIDの配列に
                        含まれているかチェックし、関連付けられている場合checkd属性が追加される（三項演算子）
                    -->
                    <input type="checkbox" name="existing_tag_ids[]" id="tag-{{ $tag->id }}" value="{{ $tag->id }}"
                        {{ in_array($tag->id, old('existing_tag_ids', $relatedTagIds)) ? 'checked' : '' }}>
                    <br>
                @endforeach
                @if (old('new_tag_names'))
                    @foreach (old('new_tag_names') as $tagName)
                        <div>
                            <label for="new-tag-{{ $tagName }}">{{ $tagName }}</label>
                            <input type="checkbox" name="new_tag_names[]" id="new-tag-{{ $tagName }}" value="{{ $tagName }}" checked>
                        </div>
                    @endforeach
                @endif
                <button type="button" id="add-new-tag">＋</button>
                <input type="text" id="new-tag-input" style="display:none;">
            </div>
            <div>
                <p>Template.</p>
                <div class="templates flex m-4">
                    @foreach($templates as $template)
                        <label class="cursor-pointer" for="template-{{ $template->id }}">
                            <input class="hidden peer" type="radio" name="diary[template_id]" id="template-{{ $template->id }}" value="{{ $template->id }}"
                                {{ old('diary.template_id') == $template->id ? 'checked' : ''}}>
                            <img class="w-36 h-auto opacity-25 peer-checked:opacity-100 shadow-xl" src="{{ asset('images/templates/' . $template->name . '.svg') }}">
                        </label>
                    @endforeach
                    <p class="template_error" style="color:red">{{ $errors->first('diary.template') }}</p>
                </div>
            </div>
            <div class="image">
                <!-- type属性にfileを指定することでファイルがアップロードできるようになる -->
                <input type="file" name="image">
                <p class="image_error" style="color:red">{{ $errors->first('diary.image') }}</p>
            </div>
            <button type="submit" style="border: none; background: none; text-decoration: underline;">
                Create Diary.
            </button>
        </form>
        <script>
            //ページが完全に読み込まれてからスクリプトを実行する
            document.addEventListener('DOMContentLoaded', function () {
                //「＋」マークをクリックしたときに新しいタグの入力欄が現れる
                document.getElementById('add-new-tag').addEventListener('click', function() {
                    var input = document.getElementById('new-tag-input');
                    input.style.display = 'block';
                    input.focus();
                });
                
                document.getElementById('new-tag-input').addEventListener('keypress', function(event) {
                    if (event.key === 'Enter') {
                        event.preventDefault();
                        
                        var tagName = event.target.value.trim();
                        if (tagName) {
                            // 新しいタグのチェックボックスを作成
                            var newTagDiv = document.createElement('div');
                            newTagDiv.innerHTML = `
                                <label for="new-tag-${tagName}">${tagName}</label>
                                <input type="checkbox" name="new_tag_names[]" id="new-tag-${tagName}" value="${tagName}" checked>
                            `;
                            
                            // 「＋」マークの前に新しいタグを挿入
                            var tagsDiv = document.querySelector('.tags');
                            var addNewTagButton = document.getElementById('add-new-tag');
                            tagsDiv.insertBefore(newTagDiv, addNewTagButton);
                            
                            // 入力欄を非表示にする
                            event.target.style.display = 'none';
                            event.target.value = '';
                        }
                    }
                });
                
                //選択された月に応じて選択可能な日の値を変更する
                const yearSelect = document.getElementById('year');
                const monthSelect = document.getElementById('month');
                const dateSelect = document.getElementById('date');
                
                function updateDays() {
                    //parseInt()で文字列を整数に変換
                    const selectedYear = parseInt(yearSelect.value);
                    const selectedMonth = parseInt(monthSelect.value);
                    //月ごとの日数を取得
                    //Dateコンストラクタに年（selectedYear）月（selectedMonth）日（0）を渡す
                    //月は0から始まり、日は1から始まる
                    //作成画面で1月を選択したとすると2月をコンストラクタに渡したことになる
                    //日に0を指定すると前月の最終日が返される
                    //getDate()で返された日付の日を取得する
                    const daysInMonth = new Date(selectedYear, selectedMonth, 0).getDate();
                    //日の選択肢をクリア
                    dateSelect.innerHTML = '';
                    //日の選択肢を再生成
                    for (let i = 1; i <= daysInMonth; i++) {
                        const option = document.createElement('option');
                        option.value = i;
                        option.textContent = i;
                        //作成したoption要素をdateSelectに追加する
                        dateSelect.appendChild(option);
                    }
                    
                    updateWeekday();
                }
                
                function updateWeekday(){
                    const selectedYear = parseInt(yearSelect.value);
                    const selectedMonth = parseInt(monthSelect.value) - 1;
                    const selectedDate = parseInt(dateSelect.value);
                    
                    const selectedDay = new Date(selectedYear, selectedMonth, selectedDate).getDay();
                    const weekdays = ['日','月','火','水','木','金','土'];
                    
                    document.getElementById('weekday').textContent = `${weekdays[selectedDay]}`;
                }
                
                yearSelect.addEventListener('change', updateDays);
                monthSelect.addEventListener('change', updateDays);
                dateSelect.addEventListener('change', updateWeekday);
                
                updateWeekday();
            });
        </script>
    </body>
</html>