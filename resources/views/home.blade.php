<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Accueil
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="">
                <x-bladewind.input name="search" can_submit placeholder="Je veux une recette..."  />
            </form>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                <div class="grid grid-cols-3 gap-4">
                    @foreach ($recipes as $recipe)
                        <a href="{{ route('recipes.show', $recipe) }}">
                            <div class="rounded overflow-hidden shadow-lg">
                                <img class="w-full" src="https://tailwindcss.com/img/card-top.jpg"
                                    alt="Sunset in the mountains">
                                <div class="px-6 py-4">
                                    <div class="font-bold text-xl mb-2">{{ $recipe->name }}</div>
                                    <p class="text-gray-700 text-base">
                                        {{ $recipe->description }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="mt-6">
                    {{ $recipes->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
