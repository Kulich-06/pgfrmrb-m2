<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function store(Request $request)
    {
     $request->validate([
     'name' => 'required',
     'login' => 'required|unique:users|min:5',
     'email' => 'email|unique:users',
     'phone' => 'required|min:11',
     'password' => 'required'
     ]);
     User::create(['password' => Hash::make($request->password)] + $request->all());
     return redirect()->route('index')->with('success','Пользователь успешно добавлен');
    }
    
    public function login(Request $request)
    {
     $request->validate([
     'login'=>'required',
     'password' =>'required'
     ]);
     if (Auth::attempt($request->only(['login','password'])))
     return redirect()->route('profile');
     return back()->withErrors([
     'login' => 'Логин или пароль неправильный'
     ]);
    }
    public function logout()
    {
     Auth::logout();
     return redirect()->route('index');
    }
    public function profile()
    {
     return view('profile');
    }
    

}
