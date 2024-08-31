<x-app-layout>
    <x-slot name="header">
        Show
    </x-slot>
    <body>
        <div class="diary">
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
        </div>
    </body>
</x-app-layout>