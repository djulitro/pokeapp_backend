<?php

namespace App\Http\Controllers;

use App\Services\PokemonService;

class PokemonController extends Controller
{
    public function primerosCienPokemons() {
        $pokemonService = new PokemonService();

        $pokemons = $pokemonService->getPokemons();

        dd($pokemons->toArray());
        
        return response()->json([
            'status' => 200,
            'data' => $pokemons,
            'message' => 'Pokemons obtenidos correctamente',
        ]);
    }
}
