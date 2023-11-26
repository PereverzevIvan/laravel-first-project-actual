<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification;
use App\Mail\AdminCommentMail;
use Illuminate\Support\Facades\Mail;
use App\Notifications\NewCommentNotify;

class CommentController extends Controller
{
    public function index() {
        $comments = Comment::latest()->paginate(10);
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
        $comment->status = true;
        $comment->save();

        $article = Article::findOrFail($comment->article_id);
        $users = User::where('id', '!=', Auth::user()->id)->get();

        Notification::send($users, new NewCommentNotify($article));
        Mail::to('peregh320@gmail.com')->send(new AdminCommentMail($comment));

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
        $comment->save();

        return redirect()->route('article.show', ['article' => $comment->article_id]);
    }

    public function delete($comment_id) {
        $comment = Comment::findOrFail($comment_id);
        Gate::authorize('comment', $comment);
        $article_id = $comment->article_id;
        $comment->delete();
        return redirect()->route('article.show', ['article' => $article_id]);
    }

    public function accept($comment_id) {
        Gate::authorize('admincomment', $comment);
        $comment = Comment::findOrFail($comment_id);
        $comment->status = true;
        $comment->save();
        
        return redirect()->route('comments');
    }

    public function reject($comment_id) {
        Gate::authorize('admincomment', $comment);
        $comment = Comment::findOrFail($comment_id);
        $comment->status = false;
        $comment->save();

        return redirect()->route('comments');
    }
}

