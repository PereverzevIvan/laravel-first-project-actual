@extends('base')

@section('title')
Статьи
@endsection

@section('content')
<h1>Это страница статьи № {{ $article->id }}!</h1>

<section class="article-section">
    <div class="article-card">
        <h2>{{ $article->name }}</h2>
        <img src='{{ asset("images/{$article->preview_image}") }}' alt="" height="300px">
        <p>{{ $article->desc }}</p>
        <p>{{ $article->date }}</p>

        <div class="button-box">
            <a href="/article/{{$article->id}}/edit" class="button button_blue">Редактировать</a>
            <form class="form-for-button" action="/article/{{ $article->id }}" method="POST"> 
                @csrf
                @method('DELETE')
                <button class="button button_red" type="submit">Удалить</button>
            </form>
        </div>
    </div>
</section>
<section class="comment-section">
    <h2>Комментарии к статье</h2>
    <form class="form" action="/comment/store" method="POST">
        @csrf
        <fieldset>
            <legend>Создание комментария</legend>
            
            <label class="form__label" for="title">Заголовок</label>
            <input class="form__input" type="text" name="title" id="title" required>

            <label class="form__label" for="text">Текст</label>
            <textarea name="text" id="text" required></textarea>

            <input type="hidden" name="article_id" value="{{ $article->id }}">
            <button class="button button_blue" type="submit">Отправить</button>
        </fieldset>
    </form>
    <div class="comments-container">
        @foreach ($comments as $comment)
            <div class="comment">
                <p class="comment__title">{{ $comment->title }}</p>
                <p class="comment__text">{{ $comment->text }}</p>
                <p class="comment__date">{{ $comment->created_at }}</p>
                <div class="button-box">
                    <a href="/comment/edit/{{ $comment->id }}" class="button button_blue">Редактировать</a>
                    <a href="/comment/delete/{{ $comment->id }}" class="button button_red">Удалить</a>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endsection 