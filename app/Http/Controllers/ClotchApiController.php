<?php

namespace App\Http\Controllers;

use App\Models\Clotch;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Resources\ClotchResource;

class ClotchApiController extends Controller
{
    public function index()
    {
        return  ClotchResource::collection(Clotch::all());
    }

    public function clotches(Category $categories)
    {
        $clotches = $categories->clotches; //получаем товары определенной категории 
        return ClotchResource::collection($clotches);
    }

    public function search(Request $request)
    {
        $query = $request->get('query');
        $clotches = Clotch::with('category')
            ->where('name', 'LIKE', "%$query%")
            ->get();
        return ClotchResource::collection($clotches);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'season_id' => 'required',
            'color_id' => 'required',
            'category_id' => 'required',
            'size' => 'required',
        ]);
        if ($request->fails())
            return response()->json(
                ['errors' => [
                    'message' => 'Validation error',
                    'errors' => $request->errors()
                ]],
                422
            );
        $clotch = Clotch::create($request->all());
        return response()->json([
            'id' => $clotch->id,
            'message' => 'Одежда добавлена'
        ], 201);
    }
}
