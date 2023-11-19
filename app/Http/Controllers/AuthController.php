<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Метод для перехода к странице регистрации
    public function registration() {
        return view('auth.register');
    }
    // Метод для создания нового пользователя в БД
    public function create_user(Request $request) {
        $request->validate([
            'name'              =>  'required',
            'email'             =>  'required|email',
            'password'          =>  'required|min:6',
            'password_repeat'   =>  'required|same:password'
        ]);

        $user = User::create([
            'name'      =>  $request->name,
            'email'     =>  $request->email,
            'password'  =>  Hash::make($request->password),
        ]);

        $user->createToken('myAppToken');

        return redirect()->route('login');
    }

    // Метод для перехода к странице авторизации
    public function login() {
        return view('auth.login');
    }

    public function authenticate(Request $request) {
        $credentials = $request->validate([
            'email'     =>  'required|email',
            'password'  =>  'required|min:6',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors('Неправильный email или пароль');
    }

    public function logOut(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
