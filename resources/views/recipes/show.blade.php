<x-app-layout>
  <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ $recipe->name }}
      </h2>
  </x-slot>

  <div class="py-12">
    {{-- Two column one with image on left and content on right --}}
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <img class="w-full" src="https://tailwindcss.com/img/card-top.jpg" alt="Sunset in the mountains">
          </div>
          <div>
            <div class="px-6 py-4">
              <div class="font-bold text-xl mb-2">{{ $recipe->name }}</div>
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

                {{-- Recipes ingredients --}}
            </div>
          </div>
        </div>
      </div>

  </div>
</x-app-layout>