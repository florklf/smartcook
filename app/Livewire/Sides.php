<?php

namespace App\Livewire;

use App\Models\Recipe;
use App\Services\OpenAIService;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;
use OpenAI\Client;

class Sides extends Component
{
    public $sides = [];

    public Recipe $recipe;

    public function mount(Recipe $recipe)
    {
        $this->recipe = $recipe;
    }

    public function render()
    {
        return view('livewire.sides', [
            'sides' => $this->sides,
        ]);
    }

    public function loadSides()
    {
        $service = new OpenAIService();
        $client = $service->getClient();
        // Proposition d'accompagnements intelligent
        $side_prompt = 'Provide a comma-separated list of side dishes that go well with "' . $this->recipe->name . '" such as wine, desserts or cheeses. Only return a comma-separated list of the side dishes. I don\'t want any other comments. Don\'t say "here is your list" or similar remarks. Answer in french.';
        $side_response = $client->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => $side_prompt],
            ],
        ]);
        $sides = $side_response['choices'][0]['message']['content'];
        $sides = explode(',', $sides);
        $this->sides = array_map(function ($value) {
            return trim($value);
        }, $sides);

        // $this->dispatch('openModal', component: 'sidesModal', arguments: ['user' => 'PROUT' ]);
    }
}
