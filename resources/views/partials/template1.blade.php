
<div class="template1 relative bg-white aspect-[1.414/1] shadow-xl" style="height: {{ $cardSize }}vh; margin: {{ $margin }}rem;">
    <div class="date-weather flex items-end absolute top-[15%] left-[8%]">
        <p class="leading-none" style="font-size: {{ $cardSize * 0.0429 }}vh;">{{ \Carbon\Carbon::parse($diary->date)->format('n.j') }}</p>
        <p class="font-bold " style="font-size: {{ $cardSize * 0.0214 }}vh; margin-left: {{ $cardSize * 0.0143 }}vh; margin-right: {{ $cardSize * 0.0143 }}vh;">{{ strtolower(\Carbon\Carbon::parse($diary->date)->format('D')) }}</p>
        <img src="{{ asset($diary->weather->icon_path) }}" style="height: {{ $cardSize * 0.0714 }}vh; transform: translateY({{ $cardSize * 0.0143 }}vh);">
    </div>
    <div class="title-body w-[32%] absolute bottom-[15%] left-[8%]">
        <p style="font-size: {{ $cardSize * 0.0214 }}vh; margin-bottom: {{ $cardSize * 0.0214 }}vh;" >▎{{ $diary->title }}</p>
        <p class="relative left-[8.5%]" style="font-size: {{ $cardSize * 0.0161 }}vh;">{!! nl2br(e($diary->body)) !!}</p>
    </div>
    <div class="tags-img">
        <p class="absolute top-[22.5%] right-[8.5%]" style="font-size: {{ $cardSize * 0.0143 }}vh;">
            @foreach ($diary->tags as $tag)
                {{ $tag->name }}@if(!$loop->last) /@endif
            @endforeach
        </p>
        <img class="w-[42%] absolute bottom-[15%] right-[8%]" src="{{ $diary->image_url }}">
    </div>
    <div class="icons m-[2.5%] absolute bottom-[0.5%] right-[0.5%]">
        <div class="space-x-4 flex">
            <div>
                <label class="cursor-pointer" for="favorite-{{ $diary->id }}">
                    <img style="height: {{ $iconSize }}vh" src="{{ in_array($diary->id, $favorites) ? '/images/icons/favorite-checked.svg' : '/images/icons/favorite.svg' }}">
                </label>
                <input class="hidden" type="checkbox" name="favorites[]" id="favorite-{{ $diary->id }}" value="{{ $diary->id }}"
                {{ in_array($diary->id, $favorites) ? "checked" : "" }}>
            </div>
            <a href="/diaries/{{ $diary->id }}/edit">
                <img style="height: {{ $iconSize }}vh" src="/images/icons/edit.svg">
            </a>
            <img class="cursor-pointer" style="height: {{ $iconSize }}vh" src="/images/icons/fullscreen.svg">
        </div>
    </div>
</div>