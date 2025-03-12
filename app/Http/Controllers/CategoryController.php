<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function create()
    {
        return view('category_create', []);
    }
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required'
    ]);

    if (auth()->check()) {
        Category::create($request->all());
        return redirect()->route('category.create')->with('success', 'Категория успешно добавлена');
    } else {
        // Сохраняем данные в localStorage на клиентской стороне
        $category = $request->input('name');
        echo "<script>
                let guestCategories = JSON.parse(localStorage.getItem('guestCategories')) || [];
                guestCategories.push({name: '{$category}'});
                localStorage.setItem('guestCategories', JSON.stringify(guestCategories));
                window.location.href = '".route('category.create')."';
              </script>";
    }
}

}
