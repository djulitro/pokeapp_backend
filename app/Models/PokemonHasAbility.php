<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PokemonHasAbility extends Model
{
    use HasFactory;

    protected $table = 'pokemon_has_abilities';

    protected $fillable = [
        'pokemon_id',
        'ability_id',
    ];
}
