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
    <div class="comments-container">
        @foreach ($comments as $comment)
            <div class="comment">
                <p class="comment__title">{{ $comment->title }}</p>
                <p class="comment__text">{{ $comment->text }}</p>
                <p class="comment__date">{{ $comment->created_at }}</p>
                <div class="button-box">
                    <a href="#" class="button button_blue">Редактировать</a>
                    <a href="#" class="button button_red">Удалить</a>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endsection