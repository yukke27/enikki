<x-app-layout>
    <x-slot name="header">
        Index
    </x-slot>
    <body>
        <h1>Enikki.</h1>
        <div class='diaries'>
            <p>ここにカレンダーが表示される</p>
        </div>
        <p class='user'>ログインユーザー：{{ Auth::user()->name }}</p>
</x-app-layout>