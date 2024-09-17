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
        <p class="selected-year-month">{{ $selectedYear }}<br>{{ $selectedMonth }}</p>
        <div class="calendar">
            @include('partials.calendar', [
                    'selectedYear' => $selectedYear, 
                    'selectedMonth' => $selectedMonth,
                ])
        </div>
        
        <div class="month-selector">
            @for ($i = 2020; $i <= now()->year; $i++)
                <!-- data属性：HTML要素に任意のデータを追加することができる -->
                <button class="year-btn" data-year="{{ $i }}" {{ $i == $selectedYear ? 'active' : '' }}>
                    {{ $i }}
                </button>
            @endfor
            <br>
            @for ($i =1; $i <= 12; $i ++)
                <button class="month-btn" data-month="{{ $i }}" {{ $i == $selectedMonth ? 'active' : '' }}>
                    {{ $i }}
                </button>
            @endfor
        </div>
        
        <p>カレンダーの表示</p>
        <p>年月選択ボタンの追加</p>
        <p>タグを収納</p>
        <p>作成・ホーム・メニューボタンの追加</p>
        <p>ロゴの追加</p>
        <div class="diaries">
            @foreach ($diaries as $diary)
                <a href="/diaries/gallery">{{ $diary->date }}</a>
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
        
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            var year = '{{ $selectedYear }}';
            var month = '{{ $selectedMonth }}';
            //ドキュメントが完全に読み込まれたときに実行される
            $(document).ready(function() {
                //classがyear-btnの要素がクリックされたときに実行される
                $('.year-btn').click(function() {
                    year = $(this).data('year');
                    month = $('.month-btn.active').data('month') || month;
                    updateCalendar(year, month);
                });
                
                $('.month-btn').click(function() {
                    month = $(this).data('month');
                    year = $('.year-btn.active').data('year') || year;
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
                            //年・月の表示を変更
                            $('.selected-year-month').html(`${year}<br>${month}`)
                            //カレンダーを更新
                            //responseオブジェクトのhtmlプロパティを使ってcalendar要素の内容を更新
                            $('.calendar').html(response.html);
                            //ボタンのアクティブ状態を更新
                            $('.year-btn').removeClass('active'); //すべてのyear-btnからactiveクラスを削除
                            $('.month-btn').removeClass('active'); //すべてのmonth-btnからactiveクラスを削除
                            $(`.year-btn[data-year="${year}"]`).addClass('active'); //選択された年に対応するボタンにactiveクラスを追加する
                            $(`.month-btn[data-month="${month}"]`).addClass('active'); //選択された月に対応するボタンにactiveクラスを追加する
                        },
                        error: function(xhr, status, error) {
                            console.error('AJAX request failed with status:', status);
                            console.error('Error message:', error);
                            console.error('Response text:', xhr.responseText);
                        }
                    });
                }
            });
        </script>
    </body>
</html>