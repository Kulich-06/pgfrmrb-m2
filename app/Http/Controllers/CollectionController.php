<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CollectionController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $collections = Collection::where('user_id', Auth::id())->get();
        } else {
            $collections = Collection::whereNull('user_id')->get();
        }

        return view('collection', compact('collections'));
    }

    public function create()
    {
        return view('collection_create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
    
        if (Auth::check()) {
            // Для авторизованных пользователей сохраняем с их user_id
            Collection::create([
                'name' => $request->name,
                'user_id' => Auth::id(),
            ]);
        } else {
            // Для неавторизованных сохраняем без user_id
            Collection::create([
                'name' => $request->name,
                'user_id' => null, // Не добавляем user_id
            ]);
        }
    
        return redirect()->route('collection.index')->with('success', 'Коллекция успешно добавлена!');
    }

    public function destroy($id)
    {
        $collection = Collection::findOrFail($id);

        if (Auth::check()) {
            // Для авторизованных пользователей — удаляем только свою коллекцию
            if ($collection->user_id == Auth::id()) {
                $collection->delete();
            }
        } else {
            // Для неавторизованных — удаляем только те, которые не принадлежат пользователям
            if ($collection->user_id === null) {
                $collection->delete();
            }
        }

        return redirect()->route('collection.index')->with('success', 'Коллекция удалена!');
    }
}
