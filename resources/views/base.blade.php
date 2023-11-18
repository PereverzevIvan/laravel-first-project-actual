<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        @yield('document_title')
    </title>
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
                        <a href="/all_articles" class="header__link">Статьи</a>
                    </li>
                    <li class="header__item">
                        <a href="/contacts" class="header__link">Контакты</a>
                    </li>
                    <li class="header__item">
                        <a href="/register" class="header__link">Регистрация</a>
                    </li>
                    <li class="header__item">
                        <a href="#" class="header__link">Вход</a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
    <main class="main">
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