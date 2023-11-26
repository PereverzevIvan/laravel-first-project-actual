<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        @yield('document_title')
    </title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header class="header">
        <div class="container">
            <a href="/" class="header__logo">Логотип</a>
            <nav class="header__nav">
                <ul class="header__list">
                    <li class="header__item">
                        <a href="/" class="header__link">Главная</a>
                    </li>
                    <li class="header__item">
                        <a href="/article" class="header__link">Статьи</a>
                    </li>
                    @can('create')
                        <li class="header__item">
                            <a href="/article/create" class="header__link">Создание статьи</a>
                        </li>
                        <li class="header__item">
                            <a href="/comment/" class="header__link">Комментарии</a>
                        </li>
                    @endcan
                    @auth
                        <li class="header__item">
                            <div class="dropdown">
                                <button class="dropbtn">Новый комментарии ({{ auth()->user()->unreadNotifications->count() }})</button>
                                <div class="dropdown-content">
                                    @foreach (auth()->user()->unreadNotifications as $notify)
                                        <a href="{{ route('article.show', ['article' => $notify->data['article']['id'], 'notify' => $notify->id]) }}" class="header__link">
                                            Статья: {{ $notify->data['article']['name'] }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </li>
                    @endauth
                    @if (Auth::user() != null)
                        <li class="header__item">
                            <a href="/logout" class="header__link">{{ Auth::user()->name }}</a>
                        </li>
                    @else
                        <li class="header__item">
                            <a href="/register" class="header__link">Регистрация</a>
                        </li>
                        <li class="header__item">
                            <a href="/login" class="header__link">Вход</a>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    </header>
    <main class="main">
        <div id="app">
            <App />
         </div>        
        <div class="container">
            @yield('content')
        </div>
    </main>
    <footer class="footer">
        <div class="container">
            <p class="common-text">Переверзев Иван Дмитриевич - 221-321</p>
        </div>
    </footer>
</body>
</html>

<style>
    /* Dropdown Button */
.dropbtn {
  background-color: #04AA6D;
  color: white;
  padding: 15px, 10px;
  font-size: 16px;
  border: none;
}

/* The container <div> - needed to position the dropdown content */
.dropdown {
  position: relative;
  display: inline-block;
}

/* Dropdown Content (Hidden by Default) */
.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f1f1f1;
  min-width: 160px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
}

/* Links inside the dropdown */
.dropdown-content a {
  color: black;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
}

/* Change color of dropdown links on hover */
.dropdown-content a:hover {background-color: #ddd;}

/* Show the dropdown menu on hover */
.dropdown:hover .dropdown-content {display: block;}

/* Change the background color of the dropdown button when the dropdown content is shown */
.dropdown:hover .dropbtn {background-color: #3e8e41;}
</style>