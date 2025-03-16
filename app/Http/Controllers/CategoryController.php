<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function create()
    {
        $categories = auth()->check() ? Category::where('user_id', auth()->id())->get() : [];
        return view('category_create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        if (auth()->check()) {
            Category::create([
                'name' => $request->input('name'),
                'user_id' => auth()->id()
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Категория успешно добавлена'
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
        $categories = auth()->check() ? Category::where('user_id', auth()->id())->get() : [];
        return view('category_index', compact('categories'));
    }
}
