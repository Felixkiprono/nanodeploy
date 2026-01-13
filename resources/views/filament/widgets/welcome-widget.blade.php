<x-filament::section>
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-semibold">
                Welcome back, {{ $user->name }}!
            </h2>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Here’s what’s happening in your infrastructure today.
            </p>
        </div>

        {{-- Optional action button (future-proof) --}}
        <div>
            <x-filament::button
                color="primary"
                icon="heroicon-o-plus"
                tag="a"
                href="{{ route('filament.panel.resources.servers.create') }}"
            >
                Create server
            </x-filament::button>
        </div>
    </div>
</x-filament::section>
