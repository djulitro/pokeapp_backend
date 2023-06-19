<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pokemon extends Model
{
    use HasFactory;

    protected $table = 'pokemons';

    protected $fillable = [
        'name',
        'height',
        'weight',
        'base_experience',
        'icon',
        'img',
        'created_at',
        'updated_at',
    ];

    public function abilities() {
        return $this->belongsToMany(Ability::class, PokemonHasAbility::class);
    }
}
