<?php

namespace App\Http\Controllers;

use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ColorController extends Controller
{
    public function create()
    {
        $colors = auth()->check() ? Color::where('user_id', auth()->id())->get() : []; 
        return view('color_create', compact('colors'));
    }
    
    
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required'
    ]);

    if (auth()->check()) {
        $color = Color::create([
            'name' => $request->input('name'),
            'user_id' => auth()->id()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Цвет успешно добавлен!',
            'color' => $color
        ]);
    } else {
        return response()->json([
            'status' => 'guest',
            'name' => $request->input('name')
        ]);
    }
}


public function index()
{
    $colors = auth()->check() ? Color::where('user_id', auth()->id())->get() : [];
    return view('color_index', compact('colors'));
}


    
    
}
