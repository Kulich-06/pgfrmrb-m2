<?php

namespace App\Http\Controllers;

use App\Models\Color;
use Illuminate\Http\Request;
use App\Http\Resources\ColorResource;

class ColorApiController extends Controller
{
    public function index()
    {
        return  ColorResource::collection(Color::all());
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);
        $color = Color::create($request->all());
        return response()->json([
            'id' => $color->id,
            'message' => 'Новый цвет создан'
        ], 201);
    }
}
