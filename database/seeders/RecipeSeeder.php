<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $recipes = [
            [
                'name' => 'Spaghetti Carbonara',
                'description' => 'Plat de pâtes italien classique.',
                'instructions' => json_encode([
                    'Cuire les spaghetti selon les instructions du paquet.',
                    'Dans une poêle séparée, cuire le bacon.',
                    'Battez les œufs avec du parmesan et du poivre noir.',
                    'Mélanger les pâtes, le bacon et le mélange d’œufs.'
                ])
            ],
            [
                'name' => 'Quiche Lorraine',
                'description' => 'Tarte salée française garnie de lardons et de fromage.',
                'instructions' => json_encode([
                    'Préparez une pâte brisée et placez-la dans un moule à tarte.',
                    'Cuire les lardons.',
                    'Mélanger les œufs, la crème et le fromage râpé.',
                    'Verser le mélange sur la pâte, ajouter les lardons, puis cuire au four.'
                ])
            ],
            [
                'name' => 'Poulet Rôti',
                'description' => 'Poulet délicieusement rôti avec des herbes.',
                'instructions' => json_encode([
                    'Préchauffez le four à 180°C.',
                    'Assaisonnez le poulet avec du sel, du poivre et des herbes.',
                    'Placez le poulet dans un plat allant au four.',
                    'Rôtir pendant 1 heure ou jusqu\'à ce que le poulet soit bien cuit.'
                ])
            ],
            [
                'name' => 'Salade Niçoise',
                'description' => 'Salade méditerranéenne rafraîchissante.',
                'instructions' => json_encode([
                    'Préparez tous les ingrédients : tomates, olives, anchois, etc.',
                    'Mélangez tous les ingrédients dans un grand bol.',
                    'Assaisonnez avec de l\'huile d\'olive, du vinaigre, du sel et du poivre.',
                    'Servez frais.'
                ])
            ],
            [
                'name' => 'Ratatouille',
                'description' => 'Un plat provençal de légumes mijotés.',
                'instructions' => json_encode([
                    'Coupez les légumes en petits morceaux.',
                    'Faites revenir les légumes dans de l\'huile d\'olive.',
                    'Assaisonnez avec des herbes de Provence.',
                    'Laissez mijoter jusqu\'à ce que les légumes soient tendres.',
                ]),
            ],
            [
                'name' => 'Coq au Vin',
                'description' => 'Un plat français classique à base de poulet et de vin rouge.',
                'instructions' => json_encode([
                    'Faites mariner le poulet dans du vin rouge pendant plusieurs heures.',
                    'Faites revenir le poulet avec des champignons et des oignons.',
                    'Ajoutez le vin mariné et laissez mijoter jusqu\'à épaississement de la sauce.',
                    'Servez chaud avec des pommes de terre ou des pâtes.',
                ]),
            ],
            [
                'name' => 'Bœuf Bourguignon',
                'description' => 'Un ragoût de bœuf mijoté avec du vin de Bourgogne.',
                'instructions' => json_encode([
                    'Coupez le bœuf en morceaux et faites-le mariner dans du vin de Bourgogne.',
                    'Dans une grande cocotte, faites revenir des lardons et des oignons.',
                    'Ajoutez la viande marinée, des carottes, et des champignons.',
                    'Versez le vin mariné et laissez mijoter jusqu\'à ce que la viande soit tendre et la sauce épaisse.',
                    'Servez chaud avec des pommes de terre ou des pâtes.',
                ]),
            ],
            [
                'name' => 'Bouillabaisse',
                'description' => 'Une soupe de poisson traditionnelle de la Provence.',
                'instructions' => json_encode([
                    'Dans une grande casserole, faites revenir des oignons, de l\'ail, et des tomates.',
                    'Ajoutez du bouillon de poisson, du vin blanc, et une variété de poissons et fruits de mer.',
                    'Assaisonnez avec des herbes provençales et du safran.',
                    'Laissez mijoter jusqu\'à ce que les fruits de mer soient cuits.',
                    'Servez chaud avec des croûtons et de la rouille (une sauce à base d\'ail et d\'huile d\'olive).',
                ]),
            ],
            [
                'name' => 'Poulet Tandoori',
                'description' => 'Poulet mariné dans une sauce au yaourt et aux épices, puis grillé.',
                'instructions' => json_encode([
                    'Mariner le poulet.',
                    'Préchauffer le four.',
                    'Cuire le poulet.'
                ]),
            ],
            [
                'name' => 'Ramen au porc',
                'description' => 'Nouilles japonaises servies dans un bouillon savoureux avec des tranches de porc.',
                'instructions' => json_encode([
                    'Préparer le bouillon.',
                    'Cuire les nouilles.',
                    'Assembler le ramen.'
                ]),
            ],
        ];
        DB::table('recipes')->insert($recipes);
    }
}
