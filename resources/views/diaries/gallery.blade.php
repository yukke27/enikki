<x-app-layout>
    <body>
        <div class="diary">
            @foreach ($diaries as $diary)
            <p>{{ $diary->created_at->format("Y-m-d") }}</p>
            <img src="{{ asset($diary->weather->icon_path) }}">
            <p>{{ $diary->title }}</p>
            <p>{{ $diary->body }}</p>
            <p>
                @foreach ($diary->tags as $tag)
                    {{ $tag->name }}@if(!$loop->last)/@endif
                @endforeach
            </p>
            <img src="{{ $diary->image_url }}">
            <label for="favorite-{{ $diary->id }}">お気に入り</label>
            <input type="checkbox" name="favorites[]" id="favorite-{{ $diary->id }}" value="{{ $diary->id }}"
            {{ in_array($diary->id, $favorites) ? "checked" : "" }}><br>
            <a href="/diaries/{{ $diary->id }}/edit">編集</a>
            @endforeach
        </div>
        
        <meta name="csrf-token" content="{{ csrf_token() }}">
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
            }
        </script>
        
    </body>
</x-app-layout>