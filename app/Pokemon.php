<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use GuzzleHttp\Client;

class Pokemon extends Model
{
    protected $table = 'pokemons';
    protected $fillable = [
        'name',
        'type'
    ];

    static function request($endpoint)
    {
        $base_url = 'http://pokeapi.salestock.net/api/v2';
        $client = new Client();
        $res = $client->request('GET', $base_url . $endpoint);
        $data = $res->getBody()->getContents();
        return response($data, Response::HTTP_CREATED);
    }
}