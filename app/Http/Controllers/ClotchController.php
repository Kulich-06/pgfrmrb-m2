<?php

namespace App\Http\Controllers;

use App\Models\Color;
use App\Models\Clotch;
use App\Models\Season;
use App\Models\Category;
use App\Models\Collection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ClotchController extends Controller
{

    public function create()
    {
        $colors = auth()->check() ? Color::where('user_id', auth()->id())->get() : [];
        $seasons = Season::all();
        $categories = auth()->check() ? Category::where('user_id', auth()->id())->get() : [];
        $collections = Collection::all();

        return view('clotch_create', [
            'colors' => $colors,
            'seasons' => $seasons,
            'categories' => $categories,
            'collections' => $collections
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
        'collections' => 'nullable|array',
        'collections.*' => 'exists:collections,id',
    ]);

    $filename = $request->file('img')->store('img');

    $clotch = Clotch::create([
        'name' => $request->name,
        'size' => $request->size,
        'img' => $filename,
        'category_id' => $request->category_id,
        'color_id' => $request->color_id,
        'season_id' => $request->season_id,
        'user_id' => auth()->id(),
    ]);

    if ($request->has('collections')) {
        $clotch->collections()->attach($request->collections);
    }

    if ($request->ajax()) {
        return response()->json([
            'status' => 'success',
            'message' => 'Одежда успешно добавлена!'
        ]);
    } else {
        return redirect()->route('clotch.index')->with('success', 'Одежда успешно добавлена!');
    }
}



    public function index()
    {
        $clotches = auth()->check()
            ? Clotch::where('user_id', auth()->id())->get()
            : collect(); // Пустая коллекция, если пользователь не авторизован

        $collections = Collection::all(); // Получаем все коллекции

        return view('index', compact('clotches', 'collections'));
    }




    public function destroy($id)
    {
        $clotch = Clotch::findOrFail($id);

        // Удаляем картинку, если она есть
       if ($clotch->img) {
    Storage::delete($clotch->img);
}


        $clotch->delete();

        return redirect()->route('clotch.index')->with('success', 'Вещь успешно удалена!');
    }
    public function selectForCollection($collection_id)
    {
        $collection = Collection::findOrFail($collection_id);
        $clotches = Clotch::where('user_id', auth()->id())->get();

        return view('clotch.select', compact('collection', 'clotches'));
    }
}
