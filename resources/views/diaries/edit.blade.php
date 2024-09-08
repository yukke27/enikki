<x-app-layout>
    <body>
        <!-- /diariesにPOSTメソッドでデータが送信される -->
        <!-- enctype属性はファイルを送信する際のデータ形式を決める -->
        <form action="/diaries/{{ $diary->id }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method("PUT")
            <div class="weather">
                @foreach($weathers as $weather)
                    <label for="weather-{{ $weather->id }}">{{ $weather->name }}</label>
                    <input type="radio" name="diary[weather_id]" id="weather-{{ $weather->id }}" value="{{ $weather->id }}"
                        {{ $diary->weather_id == $weather->id ? "checked" : "" }}>
                    <br>
                @endforeach
            </div>
            <div class="title">
                <p>タイトル</p>
                <!-- name属性はサーバー側で扱うときのキーになる -->
                <input type="text" name="diary[title]" value="{{ $diary->title }}"/>
            </div>
            <div class="body">
                <p>本文</p>
                <textarea name="diary[body]">{{ $diary->body}}</textarea>
            </div>
            <div class="image">
                <!-- type属性にfileを指定することでファイルがアップロードできるようになる -->
                <input type="file" name="image">
            </div>
            <!-- tagsテーブルの情報をチェックボックスで表示 -->
            <!-- inputタグでタグの新規作成 -->
            <div class="tags">
                @foreach($tags as $tag)
                    <label for="tag-{{ $tag->id }}">{{ $tag->name }}</label>
                    <!-- 
                        ループ内で処理されているタグが編集対象の日記に関連付けられているタグIDの配列に
                        含まれているかチェックし、関連付けられている場合checkd属性が追加される（三項演算子）
                    -->
                    <input type="checkbox" name="existing_tag_ids[]" id="tag-{{ $tag->id }}" value="{{ $tag->id }}"
                        {{ in_array($tag->id, $relatedTagIds) ? 'checked' : '' }}>
                    <br>
                @endforeach
                <button type="button" id="add-new-tag">＋</button>
                <input type="text" id="new-tag-input" style="display:none;">
            </div>
            
            <!--
            <div class="new-tags">
                <input type="text" name="new_tag_names[]" placeholder="タグを入力してEnter">
            -->
            
            </div>
            <input type="submit" value="作成"/>

        <script>
            document.getElementById('add-new-tag').addEventListener('click', function() {
                var input = document.getElementById('new-tag-input');
                input.style.display = 'block';
                input.focus();
            });

            document.getElementById('new-tag-input').addEventListener('keypress', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    
                    var tagName = event.target.value.trim();
                    if (tagName) {
                        // 新しいタグのチェックボックスを作成
                        var newTagDiv = document.createElement('div');
                        newTagDiv.innerHTML = `
                            <label for="new-tag-${tagName}">${tagName}</label>
                            <input type="checkbox" name="new_tag_names[]" id="new-tag-${tagName}" value="${tagName}">
                        `;
                        
                        // 「＋」マークの前に新しいタグを挿入
                        var tagsDiv = document.querySelector('.tags');
                        var addNewTagButton = document.getElementById('add-new-tag');
                        tagsDiv.insertBefore(newTagDiv, addNewTagButton);

                        // 入力欄を非表示にする
                        event.target.style.display = 'none';
                        event.target.value = '';
                    }
                }
            });
        </script>
        
        </form>
    </body>
</x-app-layout>