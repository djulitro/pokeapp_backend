<?php

namespace App\Services;

use App\Models\Ability;
use App\Models\Pokemon;
use App\Models\PokemonHasAbility;
use GuzzleHttp\Client;

class PokemonService {

    public function getPokemons() {
        return Pokemon::with('abilities')->get();
    }
    
    public function save(string $url) {
        $client = new Client();

        try {
            $response = $client->request('GET', $url);
            $statusCode = $response->getStatusCode();

            if($statusCode == 200) {
                $data = json_decode($response->getBody(), true);

                $abilities = $data['abilities'];
                $pokemon = Pokemon::where('id', $data['id'])->first();

                $pokemon->id = $data['id'];
                $pokemon->name = $data['name'];
                $pokemon->height = $data['height'];
                $pokemon->weight = $data['weight'];
                $pokemon->base_experience = $data['base_experience'];
                $pokemon->icon = $data['sprites']['front_default'];
                $pokemon->img = $data['sprites']['other']['home']['front_default'];
                $pokemon->save();

                $this->deletePokemonHasAbilities($pokemon->id);
                foreach ($abilities as $ability) {
                    $this->saveAbilities($ability['ability']['url'], $pokemon->id);
                }

                return response()->json($pokemon);
            } else {
                return response()->json(['error' => 'Error en la solicitud'], $statusCode);
            }

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function saveAbilities(string $url, int $pokemonId) {
        $client = new Client();

        try {
            $response = $client->request('GET', $url);
            $statusCode = $response->getStatusCode();

            if($statusCode == 200) {
                $data = json_decode($response->getBody(), true);

                $getAbility = Ability::where('id', $data['id'])->first();
                
                $abilityId = null;
                if($getAbility) {
                    $abilityId = $getAbility->id;
                } else {
                    $ability = new Ability();
                    $ability->id = $data['id'];
                    $ability->name = $data['name'];
                    $ability->save();

                    $abilityId = $ability->id;
                }

                $this->savePokemonHasAbilities($pokemonId, $abilityId);

                return;
            } else {
                return response()->json(['error' => 'Error en la solicitud'], $statusCode);
            }

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function savePokemonHasAbilities(int $pokemonId, int $abilityId) {

        $pokemonHasAbility = new PokemonHasAbility();
        $pokemonHasAbility->pokemon_id = $pokemonId;
        $pokemonHasAbility->ability_id = $abilityId;

        $pokemonHasAbility->save();
    }

    private function deletePokemonHasAbilities(int $pokemonId) {
        PokemonHasAbility::where('pokemon_id', $pokemonId)->delete();
    }
}