<?php

namespace App\Livewire;

use App\Models\Recipe;
use App\Services\OpenAIService;
use Illuminate\Support\Arr;
use Livewire\Attributes\Rule;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;
use OpenAI\Client;

class Ingredients extends Component
{
    public $ingredients = [];

    public Recipe $recipe;

    public $twitterUrl;

    public $emailUrl;

    public $currentUrl;

    #[Rule('required|gte:1')] 
    public $personsNumber = 4;

    public function mount(Recipe $recipe)
    {
        $this->currentUrl = url()->current();
        $this->recipe = $recipe;
    }

    public function render()
    {
        return view('livewire.ingredients', [
            'ingredients' => $this->ingredients,
            'recipe' => $this->recipe,
        ]);
    }

    public function updatedPersonsNumber()
    {
        $this->validate();
    }

    public function loadIngredients()
    {
        $client = OpenAIService::getClient();

        $this->validate();

        $ingredients_prompt = "Provide the list of ingredients with the quantities needed for {$this->personsNumber} people to cook a recipe titled {$this->recipe->name}.\nTo make it easier, you can extract the ingredients from the following recipe instructions if needed:\n" . implode("\n", $this->recipe->instructions) . '\n';
        $ingredients_response = $client->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => "The following is a list of ingredients needed to cook the recipe titled {$this->recipe->name}. Return only the ingredients in the completion as a comma-separated list in french language. I don\'t want any other comments. Don\'t say \"here is your list\" or similar remarks."],
                ['role' => 'user', 'content' => $ingredients_prompt],
            ],
        ]);
        $ingredients = $ingredients_response['choices'][0]['message']['content'];
        $this->ingredients = Arr::map(explode(',', $ingredients), fn ($value) => ucfirst(trim($value)));

        $this->twitterUrl = "https://twitter.com/intent/tweet?text=".urlencode("{$this->recipe->name}\n\nDescription:\n{$this->recipe->description}\n\nIngredients:\n{$ingredients}\n\nDécouvrez la recette sur {$this->currentUrl}");
        $this->emailUrl = "mailto:?subject=".urlencode("Recette: {$this->recipe->name}")."&body=Recette:{$this->recipe->name}%0D%0A%0D%0ADescription:%0D%0A{$this->recipe->description}%0D%0A%0D%0AIngredients:%0D%0A{$ingredients}%0D%0A%0D%0ADécouvrez la recette sur {$this->currentUrl}";
    }
}
