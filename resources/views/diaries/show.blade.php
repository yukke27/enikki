<x-app-layout>
    <x-slot name="header">
        Index
    </x-slot>
    <body>
        <h1>Enikki.</h1>
        <div class='diaries'>
            <h1>{{ $diary->created_at->format('Y-m-d') }}</h1>
            <img src="{{ asset($diary->weather->icon_path) }}">
            <h2>{{ $diary->title }}</h2>
            <p>{{ $diary->body }}</p>
            <p>
                @foreach ($diary->tags as $tag)
                    {{ $tag->name }}@if(!$loop->last)/@endif
                @endforeach
            </p>
            <img src="{{ asset($diary->image_path) }}">
        </div>
        <p class='user'>ログインユーザー：{{ Auth::user()->name }}</p>
</x-app-layout>