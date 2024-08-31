<x-app-layout>
    <x-slot name="header">
        Create
    </x-slot>
    <body>
        <!-- /diariesにPOSTメソッドでデータが送信される -->
        <!-- enctype属性はファイルを送信する際のデータ形式を決める -->
        <form action="/diaries" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="weather">
                <select name="diary[weather_id]">
                    <option value="1">晴れ</option>
                    <option value="2">曇り</option>
                    <option value="3">雨</option>
                    <option value="4">雪</option>
                </select>
            </div>
            <div class="title">
                <p>タイトル</p>
                <!-- name属性はサーバー側で扱うときのキーになる -->
                <input type="text" name="diary[title]" placeholder="タイトルを入力"/>
            </div>
            <div class="body">
                <p>本文</p>
                <textarea name="diary[body]" placeholder="本文を入力"></textarea>
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
                    <input type="checkbox" name="existing_tag_ids[]" id="tag-{{ $tag->id }}" value="{{ $tag->id }}">
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