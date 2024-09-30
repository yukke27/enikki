<!DOCTYPE HTML>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @vite('resources/css/app.css')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.css" integrity="sha256-jKV9n9bkk/CTP8zbtEtnKaKf+ehRovOYeKoyfthwbC8=" crossorigin="anonymous" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.6/cropper.js" integrity="sha256-CgvH7sz3tHhkiVKh05kSUgG97YtzYNnWt6OXcmYzqHY=" crossorigin="anonymous"></script>        
        <title>Create</title>
        <style>
            .cropper-view-box {
                outline: 1px solid white;
            }
            .cropper-dashed {
                border-color: white;
            }
            .cropper-point {
                background-color: white;
            }
            .cropper-point.point-se {
                width: 5px;
                height: 5px;
                background-color: white;
            }
        </style>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:ital,wght@0,100..700;1,100..700&display=swap" rel="stylesheet">
    </head>
    <body style="font-family: 'Roboto Mono', 'Noto Sans JP', sans-serif;">
        <div class="flex m-20 justify-between items-center">
            <a href="/index" class="flex w-64 h-auto">
                <img src="/images/icons/logo.svg" alt="logo">
            </a>
            <div class="flex top-20 right-20 fixed space-x-4">
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
        <form class="flex flex-col w-5/6 mx-auto mb-20" action="/diaries" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-8">
                <div class="date space-y-4">
                    <div class="flex items-center">
                        <select name="year" id="year" class="border-none focus:ring-0">
                            <!-- PHPのdate関数を使ってページを開いた時点での年を取得 -->
                            @for ($i = 2020; $i <= $currentYear; $i++)
                                <!-- $iが$currentYearと等しいときselectedを返し一致しない場合は空文字列を返す -->
                                <option value="{{ $i }}" {{ $i == $currentYear ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                        <select name="month" id="month" class="border-none focus:ring-0">
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}" {{ $i == $currentMonth ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                        <select name="date" id="date" class="border-none focus:ring-0">
                            @for ($i = 1; $i <= $daysInMonth; $i++)
                                <option value="{{ $i }}" {{ $i == $currentDate ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                        <p id="weekday" class="px-3"></p>
                    </div>
                    @if ($errors->has('date'))
                        <p class="error" style="color:red">{{ $errors->first('date') }}</p>
                    @endif
                </div>
                <div class="weather space-y-4">
                    <div class="flex flex-wrap">
                        @foreach($weathers as $weather)
                            <label class="cursor-pointer mx-4" for="weather-{{ $weather->id }}">
                                <input class="hidden peer" type="radio" name="diary[weather_id]" id="weather-{{ $weather->id }}" value="{{ $weather->id }}"
                                    {{ old('diary.weather_id') == $weather->id ? 'checked' : ''}}>
                                <img class="w-16 h-16 opacity-25 peer-checked:opacity-100" src="{{ asset('images/weathers/' . $weather->name . '.svg') }}">
                            </label>
                        @endforeach
                    </div>
                    <p class="weather_error" style="color:red">{{ $errors->first('diary.weather_id') }}</p>
                </div>
                <div class="title space-y-4">
                    <p>Title.</p>
                    <!-- name属性はサーバー側で扱うときのキーになる -->
                    <input type="text" class="min-w-96 focus:border-black focus:ring-0" name="diary[title]" placeholder="タイトルを入力" value="{{ old('diary.title') }}"/>
                    <p class="title_error" style="color:red">{{ $errors->first('diary.title') }}</p>
                </div>
                <div class="body space-y-4">
                    <p>Body.</p>
                    <textarea id="textarea" class="w-1/2 min-w-96 focus:border-black focus:ring-0 resize-none overflow-hidden" name="diary[body]" placeholder="本文を入力">{{ old('diary.body') }}</textarea>
                    <p class="body_error" style="color:red">{{ $errors->first('diary.body') }}</p>
                </div>
                <!-- tagsテーブルの情報をチェックボックスで表示 -->
                <!-- inputタグでタグの新規作成 -->
                <div class="w-1/2 min-w-[534.156px] space-y-4">
                    <p>Tags.</p>
                    <div class="tags items-center flex flex-wrap">
                        @foreach($tags as $tag)
                            <div class="py-2">
                                <label for="tag-{{ $tag->id }}" class="rounded-full my-2 mx-2 px-4 py-1 text-center border border-transparent has-[:checked]:border-black">
                                    {{ $tag->name }}<input type="checkbox" name="existing_tag_ids[]" id="tag-{{ $tag->id }}" value="{{ $tag->id }}"
                                        {{ in_array($tag->id, old('existing_tag_ids', [])) ? 'checked' : '' }} class="hidden">
                                </label>
                            </div>
                        @endforeach
                        @if (old('new_tag_names'))
                            @foreach (old('new_tag_names') as $tagName)
                                <label for="new-tag-{{ $tagName }}" class="rounded-full my-2 mx-2 px-4 py-1 text-center border border-transparent has-[:checked]:border-black">
                                    {{ $tagName }}<input type="checkbox" name="new_tag_names[]" id="new-tag-{{ $tagName }}" value="{{ $tagName }}" checked class="hidden">
                                </label>
                            @endforeach
                        @endif
                        <div class="flex items-center" id="add-new-tag">
                            <button type="button" class="rounded-full my-2 mx-2 px-4 py-1 text-center">＋</button>
                            <div class="border-b border-black">
                                <input type="text" id="new-tag-input" placeholder="新しいタグを入力" class="hidden focus:border-none focus:ring-0">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="w-1/2 min-w-[534.156px] space-y-4">
                    <p>Template.</p>
                    <div class="templates flex flex-wrap">
                        @foreach($templates as $template)
                            <label class="cursor-pointer mx-4" for="template-{{ $template->id }}">
                                <input class="hidden peer" type="radio" name="diary[template_id]" id="template-{{ $template->id }}" value="{{ $template->id }}">
                                <img class="w-48 h-auto opacity-25 peer-checked:opacity-100 shadow-xl" src="{{ asset('images/templates/' . $template->name . '.svg') }}">
                            </label>
                        @endforeach
                        <p class="template_error" style="color:red">{{ $errors->first('diary.template') }}</p>
                    </div>
                    <div class="flex justify-end">
                        <div class="flex flex-col text-right">
                            <p id="file-name" class="truncate"></p>
                            <label id="image-input-text" for="image-input" class="cursor-pointer underline text-black text-opacity-25">
                                Select and Crop the Image.
                            </label>
                            <!-- type属性にfileを指定することでファイルがアップロードできるようになる -->
                            <input type="file" name="image" id="image-input" class="hidden" disabled>
                        </div>
                    </div>
                    <p class="image_error" style="color:red">{{ $errors->first('image') }}</p>
                </div>
            </div>
            <div id="crop-modal" class="hidden fixed inset-0 bg-black bg-opacity-75 flex justify-center items-center">
                <div class="bg-white flex flex-col p-6 w-5/6 h-5/6">
                    <div class="modal-body flex-1 overflow-hidden">
                        <img id="image-preview" src="">
                    </div>
                    <div class="modal-footer flex justify-end mt-4 space-x-4">
                        <button id="cancel-button" class="border-none bg-transparent underline">cancel.</button>
                        <button id="crop-button" class="border-none bg-transparent underline">crop.</button>
                    </div>
                </div>
            </div>
            <button type="submit" class="flex justify-end mt-8 border-none bg-transparent underline">
                Create Diary.
            </button>
        </form>
        <script>
            //ページが完全に読み込まれてからスクリプトを実行する
            document.addEventListener('DOMContentLoaded', function () {
                const textarea = document.getElementById('textarea');
                if (textarea.value) {
                    textarea.style.height = 'auto';
                    textarea.style.height = textarea.scrollHeight + 'px';
                }
                textarea.addEventListener('input', function () {
                    this.style.height = 'auto';
                    //scrollHeight；コンテンツ全体が表示されるのに必要な高さが取得できる
                    this.style.height = this.scrollHeight + 'px';
                });
                
                //「＋」マークをクリックしたときに新しいタグの入力欄が現れる
                document.getElementById('add-new-tag').addEventListener('click', function() {
                    var input = document.getElementById('new-tag-input');
                    input.classList.remove('hidden'); // hiddenクラスを削除
                    input.focus();
                });
                
                //blurイベント：要素がフォーカスを失ったとき
                document.getElementById('new-tag-input').addEventListener('blur', function() {
                    var input = document.getElementById('new-tag-input');
                    input.value ='';
                    input.classList.add('hidden'); // フォーカスを失ったときにhiddenクラスを追加
                });
        
                document.getElementById('new-tag-input').addEventListener('keypress', function(event) {
                    if (event.key === 'Enter') {
                        event.preventDefault();
                        
                        var tagName = event.target.value.trim();
                        if (tagName) {
                            // 新しいタグのチェックボックスを作成
                            var newTagDiv = document.createElement('div');
                            newTagDiv.classList.add('py-2');
                            newTagDiv.innerHTML = `
                                <label for="new-tag-${tagName}" class="rounded-full my-2 mx-2 px-4 py-1 text-center border border-transparent has-[:checked]:border-black">
                                    ${tagName}<input type="checkbox" name="new_tag_names[]" id="new-tag-${tagName}" value="${tagName}" checked class="hidden">
                                </label>
                            `;
                            
                            // 「＋」マークの前に新しいタグを挿入
                            var tagsDiv = document.querySelector('.tags');
                            var addNewTagButton = document.getElementById('add-new-tag');
                            tagsDiv.insertBefore(newTagDiv, addNewTagButton);
                            
                            console.log('New tag added:', tagName); 
                            
                            // 入力欄を非表示にする
                            event.target.classList.add('hidden');
                            event.target.value = '';
                        }
                    }
                });
                
                // テンプレートが選択された際にファイル選択を有効にする
                document.querySelectorAll('input[name="diary[template_id]"]').forEach(function(input) {
                    input.addEventListener('change', function() {
                        // いずれかのテンプレートが選択された場合
                        if (document.querySelector('input[name="diary[template_id]"]:checked')) {
                            document.getElementById('image-input').disabled = false; // 有効化
                            document.getElementById('image-input-text').classList.remove('text-opacity-25');
                        }
                    });
                });
                
                /*
                document.querySelectorAll('input[name="diary[template_id]"]').forEach(function (input) {
                    input.addEventListener('change', function () {
                        console.log('テンプレートが選択されました:', this.value);
                        if (document.querySelector('input[name="diary[template_id]"]:checked')) {
                            document.getElementById('image-input').disabled = false;
                            console.log('ファイル選択ボタンが有効化されました');
                        }
                    });
                });
                */
                
                //テンプレートが選択されたときに縦横比を設定
                let selectedTemplateRatio = 1; //デフォルトの縦横比（１：１）
                //name="diary[template_id]"という属性を持つすべてのinput要素に対して行う処理
                document.querySelectorAll('input[name="diary[template_id]"]').forEach(function (input) {
                    //それぞれのinput要素に対してchangeイベントリスナー
                    input.addEventListener('change', function() {
                        if (this.value == 1) {
                            selectedTemplateRatio = 1;
                        } else if (this.value == 2) {
                            selectedTemplateRatio = 47 / 20;
                        }
                    });
                });
                
                let cropper;
                const imageInput = document.querySelector('input[type="file"]');
                const imagePreview = document.getElementById('image-preview');
                const cropModal = document.getElementById('crop-modal');
                //画像ファイル選択時にモーダル表示
                imageInput.addEventListener('change', function (event) {
                    const file = event.target.files[0];
                    const fileName = document.getElementById('file-name');
                    //ファイルが選択されていればtrue
                    if (file) {
                        fileName.textContent = file.name;
                        //FileReaderオブジェクトはローカルのファイルを非同期に読み込むために使用されるWeb API
                        const reader = new FileReader();
                        reader.readAsDataURL(file); //ファイルの読み込み形式の指定、ここではDataURL形式
                        //ファイルの読み込みが完了したときに呼び出される関数
                        reader.onload = function (e) {
                            //プレビュー用の画像src属性にセット
                            //e.targe.resultには読み込んだ画像ファイルがDataURLという形式で格納されている
                            imagePreview.src = e.target.result;
                            //以前のCropperインスタンスがあれば削除
                            if(cropper) {
                                cropper.destroy();
                            }
                            //Cropper.jsを初期化し縦横比を設定
                            //トリミング対象となる画像を指定
                            //{}内でオプションを設定、ここでは縦横比や表示モード
                            cropper = new Cropper(imagePreview, {
                                aspectRatio: selectedTemplateRatio,
                                viewMode:2, //トリミング領域の表示モード
                                ready() {
                                    const cropperBg = document.querySelector('.cropper-bg');
                                    cropperBg.style.backgroundColor = 'black';
                                    cropperBg.style.backgroundImage = 'none';
                                }
                            });
                            cropModal.classList.remove('hidden');
                        };
                    } else {
                        fileName.textContent = '';
                    }
                });
                
                const cropButton = document.getElementById('crop-button');
                const cancelButton = document.getElementById('cancel-button');
                //トリミングの適用
                cropButton.addEventListener('click', function () {
                    //フォーム送信を防ぐ
                    event.preventDefault();
                    //トリミングされた画像を<canvas>要素として取得する
                    const croppedCanvas = cropper.getCroppedCanvas();
                    //キャンバスの内容を画像ファイルとして出力する
                    //Blob（= Binary Large Object）
                    //toBlob()で生成されたBlobオブジェクトがコールバック関数の引数として渡される
                    croppedCanvas.toBlob(function (blob) {
                        //Fileコンストラクタを使ってBlobオブジェクトからファイルオブジェクトを作成
                        //第一引数にBlobオブジェクト、第二引数にファイル名を指定
                        //{}内はオプション、ここではファイルのMIMEタイプや最終更新日
                        const newFile = new File([blob], imageInput.files[0].name,{
                            type: 'image/jpeg',
                            lastModified: Date.now()
                        });
                        //DataTransferオブジェクトを作成する
                        //DataTransferオブジェクトは要素に対してプログラムからファイルを追加・操作できる
                        //input[type="file"]要素のfilesプロパティはユーザーの操作以外でファイルを選択することができない
                        //DataTransferオブジェクトを使うことでユーザーが手動でファイルを選んだかのような動作を実現する
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(newFile); //新しいファイルをDataTransferに追加
                        imageInput.files = dataTransfer.files;
                        
                        // ここで新しいファイルを確認
                        console.log('新しいファイル:', newFile);
                        console.log('選択されたファイル:', imageInput.files);
                        console.log(imageInput.files[0]);
                        
                        cropModal.classList.add('hidden');
                    });
                });
                
                //キャンセルでモーダルを閉じる
                cancelButton.addEventListener('click', function() {
                    //フォーム送信を防ぐ
                    event.preventDefault();
                    cropModal.classList.add('hidden');
                    cropper.destroy(); //トリミングをリセット
                    imageInput.value = '';
                    const fileName = document.getElementById('file-name');
                    fileName.textContent = '';
                });
                
                //選択された年・月に応じて選択可能な日の値を変更する
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
                
                //選択された日付に応じて表示される曜日を更新する
                function updateWeekday(){
                    const selectedYear = parseInt(yearSelect.value);
                    const selectedMonth = parseInt(monthSelect.value) - 1;
                    const selectedDate = parseInt(dateSelect.value);
                    
                    const selectedDay = new Date(selectedYear, selectedMonth, selectedDate).getDay();
                    const weekdays = ['sun','mon','tue','wed','thu','fri','sat'];
                    
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