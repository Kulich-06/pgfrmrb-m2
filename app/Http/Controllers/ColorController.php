<?php

namespace App\Http\Controllers;

use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function create()
    {
        return view('color_create', []);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);
    
        if (auth()->check()) {
            Color::create($request->all());
            return redirect()->route('color.create')->with('success', 'Цвет успешно добавлен');
        } else {
            // Сохраняем данные в localStorage на клиентской стороне
            $color = $request->input('name');
            echo "<script>
                    let guestColors = JSON.parse(localStorage.getItem('guestColors')) || [];
                    guestColors.push({name: '{$color}'});
                    localStorage.setItem('guestColors', JSON.stringify(guestColors));
                    window.location.href = '".route('color.create')."';
                  </script>";
        }
    }
    
}
