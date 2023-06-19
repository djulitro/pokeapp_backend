<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ability extends Model
{
    use HasFactory;

    protected $table = 'abilities';

    protected $fillable = [
        'name',
        'created_at',
        'updated_at',
    ];

    public function pokemons() {
        return $this->belongsToMany(Pokemon::class, 'pokemon_has_abilities', 'ability_id', 'pokemon_id');
    }
}
