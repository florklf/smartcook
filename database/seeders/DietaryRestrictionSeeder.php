<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DietaryRestrictionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dietaryRestrictions = [
            [
                'type' => 'allergy',
                'name' => 'gluten',
                'description' => 'Allergie ou sensibilité au gluten'
            ],
            [
                'type' => 'allergy',
                'name' => 'nuts',
                'description' => 'Allergie aux noix'
            ],
            [
                'type' => 'allergy',
                'name' => 'peanuts',
                'description' => 'Allergie aux arachides'
            ],
            [
                'type' => 'allergy',
                'name' => 'lactose',
                'description' => 'Intolérance au lactose'
            ],
            [
                'type' => 'allergy',
                'name' => 'seafood',
                'description' => 'Allergie aux fruits de mer'
            ],
            [
                'type' => 'allergy',
                'name' => 'eggs',
                'description' => 'Allergie aux œufs'
            ],
            [
                'type' => 'allergy',
                'name' => 'soy',
                'description' => 'Sensibilité ou allergie au soja'
            ],
            [
                'type' => 'diet',
                'name' => 'vegetarian',
                'description' => 'Préférence pour un régime végétarien'
            ],
            [
                'type' => 'diet',
                'name' => 'vegan',
                'description' => 'Préférence pour un régime végétalien'
            ],
            [
                'type' => 'diet',
                'name' => 'sugar-free',
                'description' => 'Régime sans sucre ou faible en sucre'
            ],
            [
                'type' => 'diet',
                'name' => 'halal',
                'description' => 'Préférence pour une alimentation Halal'
            ],
            [
                'type' => 'diet',
                'name' => 'kosher',
                'description' => 'Préférence pour une alimentation Cachère'
            ],
        ];
        DB::table('dietary_restrictions')->insert($dietaryRestrictions);
    }
}
