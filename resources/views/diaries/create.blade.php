<x-app-layout>
    <x-slot name="header">
        Create
    </x-slot>
    <body>
        <!-- /diariesにPOSTメソッドでデータが送信される -->
        <!-- enctype属性はファイルを送信する際のデータ形式を決める -->
        <form action="/diaries" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="weather">
                <select name="diary[weather_id]">
                    <option value="1">晴れ</option>
                    <option value="2">曇り</option>
                    <option value="3">雨</option>
                    <option value="4">雪</option>
                </select>
            </div>
            <div class="title">
                <p>タイトル</p>
                <!-- name属性はサーバー側で扱うときのキーになる -->
                <input type="text" name="diary[title]" placeholder="タイトルを入力"/>
            </div>
            <div class="body">
                <p>本文</p>
                <textarea name="diary[body]" placeholder="本文を入力"></textarea>
            </div>
            <div class="image">
                <!-- type属性にfileを指定することでファイルがアップロードできるようになる -->
                <input type="file" name="image">
            </div>
            <!-- tagsテーブルの情報をチェックボックスで表示 -->
            <!-- inputタグでタグの新規作成 -->
            <div class="tags">
                @foreach($tags as $tag)
                    <label for="tag-{{ $tag->id }}">{{ $tag->name }}</label>
                    <input type="checkbox" name="tags[]" id="tag-{{ $tag->id }}" value="{{ $tag->id }}">
                @endforeach
            </div>
            <input type="submit" value="作成"/>
        </form>
    </body>
</x-app-layout>