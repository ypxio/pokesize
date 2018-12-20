<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use GuzzleHttp\Client;

use App\Pokemon;

class PokemonController extends Controller
{
    public function all(Request $request)
    {
        $limit = $request->input('limit');
        $offset = $request->input('offset');
        return Pokemon::request("/pokemon?limit={$limit}&offset={$offset}");
    }
    
    public function detail($id)
    {
        return Pokemon::request("/pokemon/{$id}");
    }
}
