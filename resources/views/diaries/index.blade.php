<x-app-layout>
    <body>
        <div class="calendar">
            <p>ここにカレンダーが表示される</p>
        </div>
        <div class="diaries">
            @foreach ($diaries as $diary)
                <a href="/diaries/gallery">{{ $diary->created_at->format("Y-m-d") }}</a>
                <br>
            @endforeach
        </div>
        <form action="/tags/search" method="POST">
            @csrf
            <div class="tags">
                @foreach($tags as $tag)
                    <label for="tag-{{ $tag->id }}">{{ $tag->name }}</label>
                    <input type="checkbox" name="tag_ids[]" id="tag-{{ $tag->id }}" value="{{ $tag->id }}">
                    <br>
                @endforeach
                <button type="submit">検索</button>
            </div>
        </form>
        <a href="/diaries/create">日記を作成</a>
</x-app-layout>