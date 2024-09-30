<!DOCTYPE HTML>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @vite('resources/css/app.css')
        <title>Index</title>
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
            <div class="flex top-20 right-20 fixed space-x-4 z-10">
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
        <div class="w-5/6 mx-auto space-y-4">
            <P>Tags.</P>
            <div class="flex flex-wrap">
                @foreach($tags as $tag)
                    <p class="rounded-full my-2 mx-2 px-4 py-1 text-center border border-black">{{ $tag->name }}</p>
                @endforeach
            </div>
        </div>
        <div class="w-5/6 mx-auto mt-8 mb-16">
            <div class="diaries flex flex-wrap justify-center">
                @foreach ($diaries as $diary)
                    @if ($diary->template_id === 1)
                        @include('partials.template1', ['diary' => $diary, 'cardSize' => 30, 'iconSize' => 2.5, 'margin' => 1])
                    @elseif ($diary->template_id === 2)
                        @include('partials.template2', ['diary' => $diary, 'cardSize' => 30, 'iconSize' => 2.5, 'margin' => 1])
                    @endif
                @endforeach
            </div>
        </div>
        <script>
            //ページが完全に読み込まれたときに実行される関数を定義
            document.addEventListener('DOMContentLoaded', function() {
                //name属性が"favorites[]"のチェックボックスをすべて取得
                document.querySelectorAll('input[name="favorites[]"]').forEach(function(checkbox) {
                    //各チェックボックスに'change'イベントリスナーを追加
                    //チェックボックスの状態が変わったときに実行する処理を指定
                    checkbox.addEventListener('change', function() {
                        //handleFavoriteChange関数を呼び出す
                        //引数には現在のチェックボックス要素（変更のあったチェックボックス要素）を渡す
                        handleFavoriteChange(this);
                    });
                });
            });
            //チェックボックスが変更されたときに呼び出される関数を定義する
            function handleFavoriteChange(checkbox) {
                //チェックボックスがチェックされているかどうか
                //チェックされている場合はtrue
                const isChecked = checkbox.checked;
                const diaryId = checkbox.value;
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                //fetch関数を使ってサーバーに非同期リクエストを送信する
                //fetch関数はJavaScriptのAPIでサーバーからデータを取得・送信できる非同期関数
                //第一引数に送信先のURL
                //第二引数にリクエストの設定を含むオプションオブジェクトを指定する
                fetch('/api/favorites', {
                    method: 'POST',//HTTPメソッドを指定
                    headers: {
                        //リクエストのボディの形式を指定
                        //ここではJSON形式であることを示す
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    //Json.stringfy()を使ってJavaScriptオブジェクトをJSON形式のオブジェクトに変換
                    body: JSON.stringify({
                        diary_id: diaryId,
                        favorite: isChecked
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Success:', data);
                })
                .catch(error => {
                    console.error('Error:', error);
                });
                
                const favoriteIcon = document.querySelector(`label[for="favorite-${diaryId}"] img`);
                // isChecked が true の場合、画像を差し替える
                if (isChecked) {
                    favoriteIcon.src = '/images/icons/favorite-checked.svg'; // チェックされた状態の画像パス
                } else {
                    favoriteIcon.src = '/images/icons/favorite.svg'; // 未チェックの状態の画像パス
                }
            }
        </script>
    </body>
</html>