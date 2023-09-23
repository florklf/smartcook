<div class="space-y-2">
    <div class="flex items-center">
        <h3 class="font-bold text-xl mb-2">Ingredients</h3>
        @if ($ingredients)
            <x-bladewind.dropmenu>
                <x-slot name="trigger">
                    <x-bladewind.button size="tiny" type="secondary" class="ml-2">Partager</x-bladewind.button>
                </x-slot>
                <span onclick="copyToClipboard()">
                    <x-bladewind.dropmenu-item>
                        Copier la liste
                    </x-bladewind.dropmenu-item>
                </span>

                <a href="{{ $twitterUrl }}" id="share-twitter" target="_blank">
                    <x-bladewind.dropmenu-item>
                        <span>Partager sur Twitter</span>
                    </x-bladewind.dropmenu-item>
                </a>

                <a href="{{ $emailUrl }}" id="share-email">
                    <x-bladewind.dropmenu-item>
                        <span>Partager par email</span>
                    </x-bladewind.dropmenu-item>
                </a>
            </x-bladewind.dropmenu>
        @else
            <div class="ml-2">
                <div {{ Popper::arrow('round')->pop('Générez une liste avant de pouvoir la partager'); }}>
                    <x-bladewind.button disabled size="tiny" type="secondary">Partager</x-bladewind.button>
                </div>
            </div>
        @endif

    </div>
    
    <x-bladewind.input numeric="true" wire:model.blur="personsNumber" label="Nombre de personnes" @class(['border-red-500' => $errors->has('personsNumber')]) />
    <x-bladewind.button wire:click="loadIngredients" class="mb-2 bw-spinner" size="small">
        <div wire:loading wire:target="loadIngredients">
            <x-bladewind.spinner  />
        </div>
        <span class="ml-2">Obtenir une liste</span>
    </x-bladewind.button>

    @if ($ingredients)
        <ul class="list-disc" id="ingredients-list">
            @foreach ($ingredients as $ingredient)
                <li>{{ $ingredient }}</li>
            @endforeach
        </ul>
    @endif

    <script>
        function copyToClipboard() {
            const ingredientsList = document.getElementById('ingredients-list')
            let text = ''
            for (let i = 0; i < ingredientsList.children.length; i++) {
                text += ingredientsList.children[i].innerText + '\n'
            }
            navigator.clipboard.writeText(text);
        }
    </script>
</div>
