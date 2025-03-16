<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CollectionController extends Controller
{
    public function index()
    {
        // Для авторизованных пользователей
        if (Auth::check()) {
            $collections = Collection::where('user_id', Auth::id())->get();
        } else {
            // Для неавторизованных - показываем публичные коллекции
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
    
        // Если пользователь авторизован, привязываем коллекцию к его ID
        if (Auth::check()) {
            Collection::create([
                'name' => $request->name,
                'user_id' => Auth::id(),
            ]);
        } else {
            // Для неавторизованных пользователей создаем коллекцию без привязки к пользователю
            Collection::create([
                'name' => $request->name,
                'user_id' => null,
            ]);
        }
    
        return redirect()->route('collection.index')->with('success', 'Коллекция успешно добавлена!');
    }

    public function destroy($id)
    {
        $collection = Collection::findOrFail($id);

        // Удаляем коллекцию только если она принадлежит авторизованному пользователю
        if (Auth::check()) {
            if ($collection->user_id == Auth::id()) {
                $collection->delete();
            }
        } else {
            // Для неавторизованных пользователей удаляем только публичные коллекции
            if ($collection->user_id === null) {
                $collection->delete();
            }
        }

        return redirect()->route('collection.index')->with('success', 'Коллекция удалена!');
    }

    public function show($id)
    {
        $collection = Collection::findOrFail($id);
        return view('collection_show', compact('collection'));
    }
}
