<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    // Метод для перехода к странице регистрации
    public function create() {
        return view('auth.register');
    }
    // Метод для аунтентификации пользователя
    public function authenticate(Request $request) {
        $request->validate([
            'name'=>'required',
            'email'=>'required|email',
            'password'=>'required|min:6'
        ]);
        $response = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ];

        return $response;
    }
}
