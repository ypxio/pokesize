<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use GuzzleHttp\Client;

use App\Pokemon;

class PokemonController extends Controller
{
    public function all()
    {
        return Pokemon::request("/pokemon/");
    }
    
    public function detail($id)
    {
        return Pokemon::request("/pokemon/{$id}");
    }
}
