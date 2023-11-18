<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index() {
        return view('main.main');
    }

    public function show_all_articles() {
        $articles = json_decode(file_get_contents(public_path().'/articles.json'));
        return view('article.all_articles', ['articles' => $articles]);
    }

    public function show_one_article(Request $request) {
        $id = $request->id;
        $articles = json_decode(file_get_contents(public_path().'/articles.json'));
        $data = [];
        foreach ($articles as $article) {
            if ($article->id == $id) {
                return view('article.one_article', ['article' => $article]);
                break;
            } 
        }
    }

    public function show_about_us() {
        return view('main.about_us');
    }

    public function show_contacts() {
        $data = ['Ivan', 'Elena', 'Matvei'];
        return view('main.contacts', ['data' => $data]);
    }
}
