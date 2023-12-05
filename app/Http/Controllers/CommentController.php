<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Mail\AdminCommentMail;
use App\Mail\StatMail;
use Illuminate\Support\Facades\Mail;
use App\Notifications\NewCommentNotify;
use App\Events\newCommentEvent;

class CommentController extends Controller
{
    
    public function index() {
        $page = '0';
        if (isset($_GET['page'])) $page = $_GET['page'];

        $comments = Cache::remember('index_comments/'.$page, 3000, function () {
            return Comment::latest()->paginate(10);
        });

        return view('comment.index', ['comments' => $comments]);
    }
    
    public function store(Request $request) {
        $request->validate([
            'title' => 'required',
            'text' => 'required',
            'article_id' => 'required'
        ]);

        $comment = new Comment;
        $comment->title = $request->title;
        $comment->text = $request->text;
        $comment->author_id = Auth::id();
        $comment->article_id = $request->article_id;
        $comment->status = false;
        $res = $comment->save();

        if ($res) {
            $this->clearCacheForComments();
            
            $article = Article::findOrFail($comment->article_id);
            $users = User::where('id', '!=', Auth::user()->id)->get();

            Notification::send($users, new NewCommentNotify($article));
            Mail::to('i.d.pereverzev@mail.ru')->send(new AdminCommentMail($comment));
        }

        return redirect()->route('article.show', ['article' => $request->article_id]);
    }

    public function edit($comment_id) {
        $comment = Comment::findOrFail($comment_id);
        Gate::authorize('comment', $comment);
        return view('comment.edit_comment', ['comment' => $comment]);
    }

    public function update(Request $request, $comment_id) {
        $request->validate([
            'title' => 'required',
            'text' => 'required',
        ]);
        
        $comment = Comment::findOrFail($comment_id);
        $comment->title = $request->title;
        $comment->text = $request->text;
        $res = $comment->save();

        if ($res) {
            $this->clearCacheForComments();
            $this->clearCacheForArticle($comment->article_id);
        }

        return redirect()->route('article.show', ['article' => $comment->article_id]);
    }

    public function delete($comment_id) {
        $comment = Comment::findOrFail($comment_id);
        Gate::authorize('comment', $comment);
        $article_id = $comment->article_id;
        $res = $comment->delete();
        
        if ($res) {
            $this->clearCacheForComments();
            $this->clearCacheForArticle($comment->article_id);
        }

        return redirect()->route('article.show', ['article' => $article_id]);
    }

    public function accept($comment_id) {
        $comment = Comment::findOrFail($comment_id);

        Gate::authorize('admincomment', $comment);
        $comment->status = true;
        $res = $comment->save();

        if ($res) {
            $this->clearCacheForComments();
            $this->clearCacheForArticle($comment->article_id);
            $article = Article::findOrFail($comment->article_id);
            newCommentEvent::dispatch($article);
        }
        
        return redirect()->route('comments');
    }

    public function reject($comment_id) {
        $comment = Comment::findOrFail($comment_id);

        Gate::authorize('admincomment', $comment);
        $comment->status = false;
        $res = $comment->save();

        if ($res) {
            $this->clearCacheForComments();
            $this->clearCacheForArticle($comment->article_id);
        }

        return redirect()->route('comments');
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
