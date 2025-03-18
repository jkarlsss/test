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

        $this->description = '';
        $this->modal('create-profile')->close();
        $this->dispatch('post-created'); // Dispatch event for SweetAlert2
    }
}; ?>

<flux:modal name="create-profile" class="md:w-96">
  <form  wire:submit="savePost">
    
    <div class="space-y-6">
      <div>
        <flux:heading size="lg">Create Post</flux:heading>
        <flux:subheading>Make Post.</flux:subheading>
      </div>
      
      <flux:input wire:model="description" :label="__('Description')" type="text" required autofocus autocomplete="description" />
      
      
      <div class="flex">
        <flux:spacer />
        
        <flux:button type="submit" variant="primary">Save changes</flux:button>
        
      </div>
      
      
    </div>
  </form>
  
</flux:modal>

