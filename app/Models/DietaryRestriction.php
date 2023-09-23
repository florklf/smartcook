<?php

namespace App\Models;

use App\Enums\DietaryRestrictionTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DietaryRestriction extends Model
{
    use HasFactory;

    public function scopeAllergies($query)
    {
        return $query->where('type', DietaryRestrictionTypeEnum::Allergy->value);
    }

    public function scopeDiets($query)
    {
        return $query->where('type', DietaryRestrictionTypeEnum::Diet->value);
    }
}
