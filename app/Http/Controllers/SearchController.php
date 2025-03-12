<?php

namespace App\Http\Controllers;

use App\Models\Clotch;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');
        $clotches = Clotch::where('name', 'LIKE', "%{$query}%")->get();

        // Возвращаем результаты поиска в формате JSON
        return response()->json($clotches);
    }
}
