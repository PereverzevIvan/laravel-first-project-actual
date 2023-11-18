@extends('base')

@section('document_title')
    Все статьи
@endsection

@section('content')
    <h1>Это страница для просмотра всех статей</h1>
    <table class="article-table">
        <tr class="article-table__line">
            <th class="article-table__header">Name</th>
            <th class="article-table__header">Short Desc</th>
            <th class="article-table__header">Image</th>
            <th class="article-table__header">Date</th>
        </tr>
        @foreach($articles as $article)
            <tr class="article-table__line">
                <td class="article-table__cell">{{ $article->name }}</td>
                @if (isset($article->shortDesc))
                <td class="article-table__cell">{{ $article->shortDesc }}</td>
                @else
                <td class="article-table__cell">Нет данных</td>
                @endif
                <td class="article-table__cell"><a href="/one_article/?id={{$article->id}}"><img src='{{ asset("images/{$article->preview_image}") }}' alt="" height="100px"></a></td>
                <td class="article-table__cell">{{ $article->date }}</td>
            </tr>
        @endforeach
    </table>
@endsection