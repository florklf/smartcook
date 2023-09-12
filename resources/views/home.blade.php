<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Accueil
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
                <div class="grid grid-cols-2 gap-4">
                    @foreach ($recipes as $recipe)
                        <x-bladewind.card title="{{ $recipe->title }}">
                            <x-slot name="header">
                                <h2 class="text-xl font-bold">{{ $recipe->title }}</h2>
                            </x-slot>
                            
                            <div>
                                <p>{{ $recipe->description }}</p>
                            </div>
                        </x-bladewind.card>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>