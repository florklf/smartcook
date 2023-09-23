<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Notation;
use App\Models\User;

class Recipe extends Model
{
    use HasFactory;

    protected $casts = [
        'instructions' => 'array',
    ];

    public function notations(): HasMany
    {
        return $this->hasMany(Notation::class);
    }

    public function favoriteRecipes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorite_recipes');
    }
}
