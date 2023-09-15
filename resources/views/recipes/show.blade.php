<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $recipe->name }}
        </h2>
    </x-slot>

    <div class="py-12 grid gap-4 justify-center">
        <div class="max-w-7xl sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <img class="w-full rounded-md" src="https://tailwindcss.com/img/card-top.jpg"
                            alt="Sunset in the mountains">
                    </div>
                    <div class="px-6 py-4">
                        <div class="flex items-center mb-4">
                            <h2 class="font-bold text-xl mb-2">{{ $recipe->name }}</h2>
                            <div class="flex-1">
                                @if (Auth::user())
                                    <form action="{{ route('recipes.favorite') }}" method="POST"
                                        class="flex justify-end mb-2">
                                        @csrf
                                        <input type="hidden" name="recipe_id" value="{{ $recipe->id }}">
                                        @if ($userHasFavorited)
                                            <button type="submit" class="bg-red-500 text-white px-4 py-3 rounded">
                                                Retirer des favoris
                                            </button>
                                        @else
                                            <button type="submit" class="bg-blue-500 text-white px-4 py-3 rounded">
                                                Ajouter aux favoris
                                            </button>
                                        @endif
                                    </form>
                                @endif
                            </div>
                        </div>
                        <p class="text-gray-700 text-base">
                            {{ $recipe->description }}
                        </p>
                        <div class="mt-4">
                            <h3 class="font-bold text-xl mb-2">Instructions</h3>
                            <ol class="list-decimal">
                                @foreach ($recipe->instructions as $instruction)
                                    <li>{{ $instruction }}</li>
                                @endforeach
                            </ol>

                            <div class="flex items-center mb-2 mt-4">
                                <h3 class="font-bold text-xl mr-1">Ingredients</h3>
                                <x-bladewind.dropmenu>
                                    <span onclick="copyToClipboard()">
                                        <x-bladewind.dropmenu-item>
                                            Share Clipboard
                                        </x-bladewind.dropmenu-item>
                                    </span>

                                    <a href="" id="share-twitter" target="_blank">
                                        <x-bladewind.dropmenu-item>
                                            <span>Share Twitter</span>
                                        </x-bladewind.dropmenu-item>
                                    </a>

                                    <a href="" id="share-email">
                                        <x-bladewind.dropmenu-item>
                                            <span>Share Email</span>
                                        </x-bladewind.dropmenu-item>
                                    </a>
                                </x-bladewind.dropmenu>
                            </div>
                            <ul class="list-disc" id="ingredients-list">
                                @foreach ($ingredients as $ingredient)
                                    <li>{{ $ingredient }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Related recipes --}}
        <div class="max-w-7xl sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                <h3 class="font-bold text-xl my-2">Recettes similaires</h3>
                <div class="grid grid-cols-4 gap-4">
                    @foreach ($related_recipes as $related_recipe)
                        <a href="{{ route('recipes.show', $related_recipe->id) }}">
                            <div class="max-w-sm rounded overflow-hidden shadow-lg">
                                <img class="w-full" src="https://tailwindcss.com/img/card-top.jpg"
                                    alt="Sunset in the mountains">
                                <div class="px-6 py-4">
                                    <div class="font-bold text-xl mb-2">{{ $related_recipe->name }}</div>
                                    <p class="text-gray-700 text-base">
                                        {{ $related_recipe->description }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Comments --}}
        @if (Auth::user())
            <div class="min-w-7xl max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                    <div class="mb-4">
                        @if (session('success'))
                            <div class="bg-green-500 p-4 rounded-lg mb-6 text-white text-center">
                                {{ session('success') }}
                            </div>
                        @endif
                    </div>
                    @if ($userHasNotated)
                        <form action="{{ route('notations.store') }}" method="POST" onsubmit="return checkform()"
                            novalidate>
                            @csrf
                            <input type="hidden" name="recipe_id" value="{{ $recipe->id }}">
                            <div class="mb-4">
                                <label class="text-xl text-gray-600">Comment</label><br>
                                <div class="flex items-center flex-row-reverse">
                                    <input type="radio" id="star5" name="notation" value="5"
                                        class="hidden" />
                                    <label for="star5" class="text-2xl text-gray-300 cursor-pointer">&#9733;</label>

                                    <input type="radio" id="star4" name="notation" value="4"
                                        class="hidden" />
                                    <label for="star4" class="text-2xl text-gray-300 cursor-pointer">&#9733;</label>

                                    <input type="radio" id="star3" name="notation" value="3"
                                        class="hidden" />
                                    <label for="star3" class="text-2xl text-gray-300 cursor-pointer">&#9733;</label>

                                    <input type="radio" id="star2" name="notation" value="2"
                                        class="hidden" />
                                    <label for="star2" class="text-2xl text-gray-300 cursor-pointer">&#9733;</label>

                                    <input type="radio" id="star1" name="notation" value="1"
                                        class="hidden" />
                                    <label for="star1" class="text-2xl text-gray-300 cursor-pointer">&#9733;</label>
                                </div>
                                <textarea name="comment" class="border-2 border-gray-300 p-2 w-full" rows="3"></textarea>
                            </div>
                            <div class="mb-8">
                                <button type="submit"
                                    class="bg-blue-500 text-white px-4 py-3 rounded font-medium w-full">Post
                                    Comment</button>
                            </div>
                        </form>
                    @endif

                    <div class="my-4">
                        <h3 class="font-bold text-xl mb-2">Comments</h3>
                        @if ($recipe->notations->count() == 0)
                            <p class="text-gray-700 text-base">
                                No comments yet.
                            </p>
                        @endif
                        @foreach ($notations as $notation)
                            <x-bladewind.card title="{{ $notation->user->name }}">
                                <x-bladewind.rating name="thumb-rating" rating="{{ $notation->notation }}"
                                    size="small" clickable={{ false }} />
                                <x-slot name="footer">
                                    {{ $notation->comment }}
                                </x-slot>
                            </x-bladewind.card>
                        @endforeach
                    </div>

                </div>
            </div>
        @endif
    </div>

    <style>
        input[type="radio"]:checked+label,
        input[type="radio"]:checked+label~label {
            color: orange;
        }
    </style>

    <script>
        document.onreadystatechange = function() {
            if (document.readyState === 'complete') {
                shareEmail();
                shareTwitter();
            }
        }

        function checkform() {
            var selectedRating = document.querySelector('input[name="notation"]:checked');
            if (!selectedRating) {
                alert("Please select a rating!");
                return false;
            }
            return true;
        }

        function copyToClipboard() {
            const ingredientsList = document.getElementById('ingredients-list')
            let text = ''
            for (let i = 0; i < ingredientsList.children.length; i++) {
                text += ingredientsList.children[i].innerText + '\n'
            }
            navigator.clipboard.writeText(text);
        }

        function shareTwitter() {
            const recipeName = <?php echo json_encode($recipe->name); ?>;
            const recipeDescription = <?php echo json_encode($recipe->description); ?>;
            const recipeInstructions = <?php echo json_encode($recipe->instructions); ?>;
            const recipeIngredients = document.getElementById('ingredients-list').innerText;
            const twitterUrl =
                `https://twitter.com/intent/tweet?text=${encodeURIComponent(`${recipeName}\n\nDescription:\n${recipeDescription}\n\nD\écouvrez la recette sur ${window.location.href}`)}`;
            document.getElementById('share-twitter').href = twitterUrl;
        }

        function shareEmail() {
            const recipeName = <?php echo json_encode($recipe->name); ?>;
            const recipeDescription = <?php echo json_encode($recipe->description); ?>;
            const recipeInstructions = <?php echo json_encode($recipe->instructions); ?>;
            const recipeIngredients = document.getElementById('ingredients-list').innerText;
            const emailBody =
                `Recipe: ${recipeName}\n\nDescription:\n${recipeDescription}\n\nInstructions:\n${recipeInstructions}\n\nIngredients:\n${recipeIngredients}`;
            const email = `mailto:?subject=Recipe: ${recipeName}&body=${emailBody}`;
            document.getElementById('share-email').href = email;
        }
    </script>
</x-app-layout>
