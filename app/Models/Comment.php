<?php

namespace App\Models;

use App\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Article;

class Comment extends Model
{
    use HasFactory;

    public function getAuthorName() {
        return User::find($this->author_id)->name;
    }

    public function article() {
        return $this->belongTo(Article::class);
    }

    public function user() {
        return $this->belongTo(User::class);
    }
}
