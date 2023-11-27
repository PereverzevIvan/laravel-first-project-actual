<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Jobs\ArticleMailJob;
use App\Http\Controllers\CommentController;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $page = '0';
        if (isset($_GET['page'])) $page = $_GET['page'];

        $articles = Cache::remember('articles'.$page, 3000, function () {
            return Article::latest()->paginate(6);
        });
        return view('article.all_articles', ['articles' => $articles]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', [self::class]);
        return view('article.create_article');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'short_desc'=>'required|min:6',
            'desc'=>'required|min:6',
            'date'=>'required',
        ]);

        $article = new Article;
        $article->name = $request->name;
        $article->short_desc = $request->short_desc;
        $article->desc = $request->desc;
        $article->date = $request->date;
        $article->author_id = 1;
        $res = $article->save();

        if ($res) {
            $this->clearCacheForAllArticles();
            ArticleMailJob::dispatch($article);
        }

        return redirect('/article');
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        if (isset($_GET['notify'])) {
            auth()->user()->notifications->where('id', $_GET['notify'])->first()->markAsRead();
        }

        $page = '0';
        if (isset($_GET['page'])) $page = $_GET['page'];

        $comments = Cache::remember('comments/'.$article->id.'/'.$page, 3000, function ()use($article) {
            return Comment::where('article_id', $article->id)->where('status', 1)->latest()->paginate(2);
        });

        return view('article.one_article', ['article' => $article, 'comments' => $comments]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        Gate::authorize('update', [self::class]);
        return view('article.edit_article', ['article' => $article]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Article $article)
    {
        Gate::authorize('update', [self::class]);

        $request->validate([
            'name'=>'required',
            'short_desc'=>'required|min:6',
            'desc'=>'required|min:6',
            'date'=>'required',
        ]);

        $article->name = $request->name;
        $article->short_desc = $request->short_desc;
        $article->desc = $request->desc;
        $article->date = $request->date;
        $res = $article->save();

        if ($res) {
            $this->clearCacheForArticle($article->id);
            $this->clearCacheForAllArticles();
        }
        return redirect()->route('article.show', ['article' => $article]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        Gate::authorize('delete', [self::class]);
        $comments = Comment::where('article_id', $article->id)->delete();
        $res = $article->delete();

        if ($res) {
            $this->clearCacheForArticle($article->id);
            $this->clearCacheForComments();
            $this->clearCacheForAllArticles();
        }

        return redirect('/article');
    }

    public function clearCacheForAllArticles($article_id=null) {
        $keys = DB::table('cache')->whereRaw('`key` GLOB :key', [':key' => 'articles*[0-9]'])->get();
            foreach($keys as $key) {
            Cache::forget($key->key);
        }
    }

    public function clearCacheForArticle($article_id=null) {
        if (isset($article_id)) {
            $keys = DB::table('cache')->whereRaw('`key` GLOB :key', [':key' => 'comments/'.$article_id.'/*[0-9]'])->get();
            foreach($keys as $key) {
                Cache::forget($key->key);
            }    
        }
    }

    public function clearCacheForComments() {
        $keys = DB::table('cache')->whereRaw('`key` GLOB :key', [':key' => 'index_comments/*[0-9]'])->get();
        foreach($keys as $key) {
            Cache::forget($key->key);
        }
    }
}
