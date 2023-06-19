<?php

namespace App\Console\Commands;

use App\Services\PokemonService;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;

class ActualizarPokemon extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:actualizar-pokemon';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualizar los pokemons de la API PokeApi.co';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $client = new Client();
        $url = "https://pokeapi.co/api/v2/pokemon?limit=100&offset=0";
        $pokemonService = new PokemonService();
    
        try {
            $response = $client->request('GET', $url);
            $statusCode = $response->getStatusCode();
    
            if ($statusCode == 200) {
                $data = json_decode($response->getBody(), true);
                $results = $data['results'];
                $progressBar = $this->progressBar(count($results), $this->output);
                foreach ($results as $value) {
                    $pokemonService->save($value['url']);
                    $progressBar->advance();
                }

                $progressBar->finish();

                return $this->info('Se actualizaron los pokemons');
            } else {
                return $this->error('Error en la solicitud');
            }
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    private function progressBar($total, $output) {
        $progressBar = new ProgressBar($output, $total);
        
        $progressBar->setBarCharacter('<fg=green>·</>');
        $progressBar->setEmptyBarCharacter('-');
        $progressBar->setProgressCharacter('>');
        $progressBar->setFormat(' %current%/%max% [%bar%] %percent:3s%%');

        $progressBar->start();

        return $progressBar;
    }
}
