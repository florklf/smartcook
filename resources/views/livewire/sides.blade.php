<div class="space-y-2">
    <h3 class="font-bold text-xl mb-2">Accompagnements</h3>
    <x-bladewind.button wire:click="loadSides" class="mb-2 bw-spinner" size="small">
        <div wire:loading>
            <x-bladewind.spinner  />
        </div>
        <span class="ml-2">Obtenir des propositions</span>
    </x-bladewind.button>
    @if ($sides)
        <ul class="list-disc">
            @foreach ($sides as $side)
                <li>{{ Str::ucfirst($side) }}</li>
            @endforeach
        </ul>
    @endif
</div>
