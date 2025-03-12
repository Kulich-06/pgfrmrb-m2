<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryResource;

class CategoryApiController extends Controller
{
    public function index()
    {
        return  CategoryResource::collection(Category::all());
    }

    public function clotches(Category $category)
    {
        return $category->clotches;
    }

    public  function store(Request $request)
    {

        $request->validate([
            'name' => 'required'
        ]);

        $category = Category::create($request->all());

        return response()->json([
            'id' => $category->id,
            'message' => 'Категория создана'
        ], 201);
    }
}
