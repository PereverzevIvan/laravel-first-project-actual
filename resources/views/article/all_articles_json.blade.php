@extends('base')

@section('title')
Статьи
@endsection

@section('content')
<h1>Это страница для просмотра всех статей!</h1>
<table>
    <tr>
        <th>Name</th>
        <th>Short Desc</th>
        <th>Image</th>
        <th>Date</th>
    </tr>
    @foreach($articles as $article)
        <tr>
            <td>{{ $article->name }}</td>
            @if (isset($article->shortDesc))
            <td>{{ $article->shortDesc }}</td>
            @else
            <td>Нет данных</td>
            @endif
            <td><a href="/one_article/?id={{ $article->id }}"><img src='{{ asset("images/{$article->preview_image}") }}' alt="" height="100px"></a></td>
            <td>{{ $article->date }}</td>
        </tr>
    @endforeach
</table>

@endsection