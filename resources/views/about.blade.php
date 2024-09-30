<!DOCTYPE HTML>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @vite('resources/css/app.css')
        <title>About</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:ital,wght@0,100..700;1,100..700&display=swap" rel="stylesheet">
    </head>
    <body style="font-family: 'Roboto Mono', 'Noto Sans JP', sans-serif;">
        <div class="flex justify-end m-16 space-x-4">
            @guest
                <a class="underline" href="{{ route('register') }}">Sign up.</a>
                <a class="underline" href="{{ route('login') }}">Log in.</a>
            @else
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="underline">Log out.</button>
                </form>
                <!--
                <span class="invisible">Sign up.</span>
                <span class="invisible">Log in.</span>
                -->
            @endguest
        </div>
        <div class="flex flex-col w-1/2 mx-auto">
            <img class="w-80 mx-auto my-36" src="/images/icons/logo.svg">
            <div class="text-left tracking-[0.3rem] w-full mb-16 space-y-16">
                <div class="space-y-8">
                    <p class="text-2xl font-semibold">Enikki.でできること</p>
                    <p class="text-xl font-light ml-20">
                        Enikki.では、あなたの日常を簡単に記録することができます。<br>
                        誰かに見せるためじゃない、自分だけの日記をつくってみませんか。
                    </p>
                </div>
                <div class="space-y-8">
                    <p class="text-2xl font-semibold">自分のために日々を記録する</p>
                    <p class="text-xl font-light ml-20">
                        Enikki.では、１枚の写真と短い文章で１日を振り返ることができます。<br>
                        SNSにその日の出来事を投稿するのが当たり前に
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>