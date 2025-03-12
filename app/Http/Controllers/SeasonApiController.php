<?php

namespace App\Http\Controllers;

use App\Models\Season;
use Illuminate\Http\Request;
use App\Http\Resources\SeasonResource;

class SeasonApiController extends Controller
{
    public function index()
    {
        return  SeasonResource::collection(Season::all());
    }
}
