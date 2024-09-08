<x-app-layout>
    <body>
        <div class="diaries">
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
            <a href="/diaries/{{ $diary->id }}/edit">編集</a>
            @endforeach
        </div>
</x-app-layout>