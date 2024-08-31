<x-app-layout>
    <x-slot name="header">
        Index
    </x-slot>
    <body>
        <h1>Enikki.</h1>
        <div class="calendar">
            <p>ここにカレンダーが表示される</p>
        </div>
        <div class="diaries">
            @foreach ($diaries as $diary)
                <a href="/diaries/{{ $diary->id }}">{{ $diary->title }}</a>
                <br>
            @endforeach
        </div>
        <a href="/diaries/create">日記を作成</a>
        <p class="user">ログインユーザー：{{ Auth::user()->name }}</p>
</x-app-layout>