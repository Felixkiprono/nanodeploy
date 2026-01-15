<x-filament::section
    heading="NanoDeploy Server SSH Key"
    description="Use this key to grant NanoDeploy access to your server."
    icon="heroicon-o-key"
>
    <div class="max-w-4xl">
        <div class="rounded-xl border border-gray-800 bg-gray-900 p-6 space-y-4">

            {{-- Header row --}}
            <div class="flex items-center justify-between">
                <span class="text-sm font-medium text-gray-300">
                    Server Public Key
                </span>

                <x-filament::button
                    size="xs"
                    color="gray"
                    icon="heroicon-o-clipboard"
                    onclick="navigator.clipboard.writeText(document.getElementById('nanodeploy-public-key').value)"
                >
                    Copy
                </x-filament::button>
            </div>

            {{-- Textarea (THIS was missing / broken before) --}}
            <textarea
                id="nanodeploy-public-key"
                readonly
                rows="5"
                cols="50"
                class="w-full rounded-lg bg-gray-950 text-sm text-gray-100 font-mono
                       border border-gray-700 shadow-inner
                       leading-relaxed resize-none"
            >{{ $sshKey->public_key }}</textarea>

            {{-- Helper text --}}
            <p class="text-xs text-gray-500">
                Add this key to
                <code class="text-gray-300">~/.ssh/authorized_keys</code>
                on your server. This key is safe to share.
                <strong class="text-gray-400">Never expose the private key.</strong>
            </p>
        </div>

        {{-- Actions --}}
       {{-- Action Divider --}}
<div class="mt-6 pt-5">
    <div class="h-px w-full bg-gradient-to-r from-transparent via-gray-800 to-transparent mb-4"></div>

    <div class="flex items-center gap-3">
        <x-filament::button
            icon="heroicon-o-clipboard"
            onclick="navigator.clipboard.writeText(document.getElementById('nanodeploy-public-key').value)"
        >
            Copy Public Key
        </x-filament::button>

        <x-filament::button
            color="gray"
            icon="heroicon-o-eye"
            :href="\App\Filament\Resources\SSHKeys\SSHKeyResource::getUrl('view', ['record' => $sshKey])"
        >
            View SSH Key Details
        </x-filament::button>
    </div>
</div>

    </div>
</x-filament::section>
