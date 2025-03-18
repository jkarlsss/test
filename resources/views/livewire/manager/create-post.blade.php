<?php

use App\Models\Post;
use Livewire\Volt\Component;

new class extends Component {

    public string $description = '';

    public function savePost(): void {

        $validated = $this->validate([
            'description' => ['required', 'string', 'min:3', 'max:255'],
        ]);


        if (! auth()->check()) {
            $this->redirect(route('login', absolute: false));
        }

        $validated['user_id'] = auth()->id(); // Add the authenticated user's ID
        Post::create($validated);

        $this->redirectIntended(route('manager', absolute: false), navigate: true);
    }
}; ?>

<div>
    <x-manage.layout>
        <form wire:submit="savePost" class="my-6 w-full flex gap-2 justify-center items-center space-y-6">
            <flux:input wire:model="description" :label="__('Description')" type="text" required autofocus autocomplete="description" />

            <div class="flex items-center gap-4">
                <div class="flex items-center justify-end">
                    <flux:button variant="primary" type="submit" class="w-full">{{ __('Save') }}</flux:button>
                </div>

                <x-action-message class="me-3" on="profile-updated">
                    {{ __('Saved.') }}
                </x-action-message>
            </div>
        </form>
    </x-manage.layout>
</div>
