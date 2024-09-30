<!DOCTYPE HTML>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @vite('resources/css/app.css')
        <title>Home</title>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:ital,wght@0,100..700;1,100..700&display=swap" rel="stylesheet">
    </head>
    <body style="font-family: 'Roboto Mono', 'Noto Sans JP', sans-serif;">
        <div class="flex flex-col">
            <div class="flex m-20 justify-between items-center">
                <a href="/index" class="flex w-64 h-auto">
                    <img src="images/icons/logo.svg" alt="logo">
                </a>
                <div class="flex top-20 right-20 fixed space-x-4">
                    <a href="/diaries/create" class="w-12 h-12 rounded-full bg-white border border-black flex justify-center items-center">
                        <img src="images/icons/add.svg" alt="new diary" class="w-8 h-8 object-cover">
                    </a>
                    <a href="/index" class="w-12 h-12 rounded-full bg-white border border-black flex justify-center items-center">
                        <img src="images/icons/home.svg" alt="home" class="w-8 h-8 object-cover">
                    </a>
                    <a href="" class="w-12 h-12 rounded-full bg-white border border-black flex justify-center items-center">
                        <img src="images/icons/menu.svg" alt="menu" class="w-8 h-8 object-cover">
                    </a>
                </div>
            </div>
            
            <div class="flex flex-col xl:flex-row justify-center mt-8">
                <div class="flex-1 hidden xl:block"></div>
                <div class="calendar-year-month-selector mx-auto flex flex-col items-center">
                    <div class="calendar w-[36rem] h-[50rem] flex flex-col justify-center shadow-xl">
                        @include('partials.calendar', [
                                'selectedYear' => $selectedYear, 
                                'selectedMonth' => $selectedMonth,
                                'datesWithPosts' => $datesWithPosts,
                            ])
                    </div>
                    <div class="year-month-selector mt-16 mb-12">
                        <div class="year-selector">
                            @for ($i = 2023; $i <= now()->year; $i++)
                                <!-- data属性：HTML要素に任意のデータを追加することができる -->
                                <button class="year-btn my-2 mx-2 w-14 flex-row items-center justify-center rounded-full {{ $i == $selectedYear ? 'border border-black' : '' }}" data-year="{{ $i }}">
                                    {{ $i }}
                                </button>
                            @endfor
                        </div>
                        <div class="month-selector">
                            @for ($i =1; $i <= 12; $i ++)
                                <button class="month-btn my-2 mx-2 w-8 flex-row items-center justify-center rounded-full {{ $i == $selectedMonth ? 'border border-black' : '' }}" data-month="{{ $i }}">
                                    {{ $i }}
                                </button>
                            @endfor
                        </div>
                    </div>
                </div>
                <div class="tags flex-1 xl:mt-8">
                    <div class="w-[42rem] mx-auto mb-16 xl:w-[18rem] 2xl:w-[24rem] xl:mx-auto xl:mb-0">
                        <button id="toggle-tags" class="mb-4">Tags∨</button>
                        <form action="/tags/search" method="POST">
                            @csrf
                            <div id="tag-list" class="tags hidden flex flex-wrap">
                                @foreach($tags as $tag)
                                    <label for="tag-{{ $tag->id }}" class="rounded-full my-2 mx-2 px-4 py-1 text-center border border-transparent has-[:checked]:border-black">
                                        {{ $tag->name }}<input type="checkbox" name="tag_ids[]" id="tag-{{ $tag->id }}" value="{{ $tag->id }}" class="hidden">
                                    </label>
                                @endforeach
                                <button type="submit" class="rounded-full my-2 mx-2 px-2 py-1 text-center">→</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        

        <script>
            var year = '{{ $selectedYear }}';
            var month = '{{ $selectedMonth }}';
            //ドキュメントが完全に読み込まれたときに実行される
            $(document).ready(function() {
                //classがyear-btnの要素がクリックされたときに実行される
                $('.year-btn').click(function() {
                    year = $(this).data('year');
                    month = $('.month-btn.border').data('month') || month;
                    updateCalendar(year, month);
                });
                
                $('.month-btn').click(function() {
                    month = $(this).data('month');
                    year = $('.year-btn.border').data('year') || year;
                    updateCalendar(year, month);
                });
                
                function updateCalendar(year, month) {
                    console.log('Requesting calendar update with year:', year, 'and month:', month);
                    
                    $.ajax({
                        url: '/diaries/updateCalendar', //送信先のURL
                        type: 'GET',
                        data: { year: year, month: month },
                        success: function(response) {
                            console.log('Response received:', response); 
                            console.log(year);
                            console.log(month);
                            //カレンダーを更新
                            //responseオブジェクトのhtmlプロパティを使ってcalendar要素の内容を更新
                            $('.calendar').html(response.html);
                            //ボタンのアクティブ状態を更新
                            $('.year-btn').removeClass('border border-black'); //すべてのyear-btnからborderクラスを削除
                            $('.month-btn').removeClass('border border-black'); //すべてのmonth-btnからborderクラスを削除
                            $(`.year-btn[data-year="${year}"]`).addClass('border border-black'); //選択された年に対応するボタンにborderクラスを追加する
                            $(`.month-btn[data-month="${month}"]`).addClass('border border-black'); //選択された月に対応するボタンにborderクラスを追加する
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX request failed with status:', status);
                            console.error('Error message:', error);
                            console.error('Response text:', xhr.responseText);
                        }
                    });
                }
                
                //タグの表示・非表示
                $('#toggle-tags').click(function() {
                    const tagList = $('#tag-list');
                    if (tagList.hasClass('hidden')) {
                        tagList.removeClass('hidden');
                        $(this).text('Tags∧');
                    } else {
                        tagList.addClass('hidden');
                        $(this).text('Tags∨');
                    }
                });
            });
        </script>
    </body>
</html>