@extends('base')

@section('title')
Статьи
@endsection

@section('content')
<h1>Это страница статьи № {{ $article->id }}!</h1>
<h2>{{ $article->name }}</h2>
<img src='{{ asset("images/{$article->preview_image}") }}' alt="" height="300px">
<p>{{ $article->desc }}</p>
<p>{{ $article->date }}</p>

<a href="/article/{{$article->id}}/edit" class="button button_blue">Редактировать</a>
<form class="form-for-button" action="/article/{{ $article->id }}" method="POST"> 
    @csrf
    @method('DELETE')
    <button class="button button_red" type="submit">Удалить</button>
</form>
@endsection