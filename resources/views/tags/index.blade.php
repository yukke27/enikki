<!DOCTYPE HTML>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Index</title>
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    </head>
    <body>
        <p>タグ一覧の表示</p>
        <div class="diaries">
            @foreach ($diaries as $diary)
            <p>{{ $diary->date }}</p>
            <img src="{{ asset($diary->weather->icon_path) }}">
            <p>{{ $diary->title }}</p>
            <p>{{ $diary->body }}</p>
            <p>
                @foreach ($diary->tags as $tag)
                    {{ $tag->name }}@if(!$loop->last)/@endif
                @endforeach
            </p>
            <img src="{{ $diary->image_url }}" style="width: 500px;"><br>
            <a href="/diaries/{{ $diary->id }}/edit">編集</a>
            @endforeach
        </div>
</html>