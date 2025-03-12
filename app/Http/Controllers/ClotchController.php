<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\Clotch;
use App\Models\Season;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ClotchController extends Controller
{

    public function create()
    {
        $colors = Color::all();
        $seasons = Season::all();
        $categories = Category::all();
        return view('clotch_create', [
            'colors' => $colors,
            'seasons' => $seasons,
            'categories' => $categories
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'size' => 'required|string|max:10',
            'img' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category_id' => 'required|exists:categories,id',
            'color_id' => 'required|exists:colors,id',
            'season_id' => 'required|exists:seasons,id',
        ]);
    
        $filename = $request->file('img')->store('img');
        Clotch::create(['img' => $filename, 'user_id' => auth()->id()] + $request->all());
    
        $validated['user_id'] = auth()->id(); // 👈 Добавляем user_id
    
        return redirect()->route('clotch.index')->with('success', 'Одежда успешно добавлена!');
    }
    
    
    public function index()
    {
        $clotches = auth()->check() 
            ? Clotch::where('user_id', auth()->id())->get() 
            : collect();
    
        return view('index', compact('clotches'));
    }
    
    

    public function destroy($id)
{
    $clotch = Clotch::findOrFail($id);
    
    // Удаляем картинку, если она есть
    if ($clotch->img) {
        Storage::delete('storage/app/' . $clotch->img);
    }

    $clotch->delete();

    return redirect()->route('clotch.index')->with('success', 'Вещь успешно удалена!');
}

}
