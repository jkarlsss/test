<?php

use App\Models\Post;
use Livewire\Volt\Component;
use Livewire\WithPagination;

new class extends Component {
  use WithPagination;

  public string $description = "";
  public ?int $editingPostId = null; // Track the post being edited
  public array $postIds = [];
    
  public function with(): array {
    return [
      'posts' => Post::join('users', 'posts.user_id', '=', 'users.id')
        ->select('posts.*', 'users.name as user_name')
        ->orderBy('posts.created_at', 'desc')
        ->paginate(10),
    ];
  }
  public function savePost(): void {

    if (! auth()->check()) {
        $this->redirect(route('login', absolute: false));
        return;
    }
    $validated = $this->validate([
        'description' => ['required', 'string', 'min:3', 'max:255'],
    ]);


    $validated['user_id'] = auth()->id(); // Add the authenticated user's ID
    Post::create($validated);

    $this->description = '';
    $this->postIds = [];
    $this->modal('create-profile')->close();
    $this->js('alertFunction', 'Successfully created post!', 'success'); // Dispatch event for SweetAlert2
  }

  public function editPost($id): void {
    if (! auth()->check()) {
        $this->redirect(route('login', absolute: false));
        return;
    }
    $post = Post::findOrFail($id);
    $this->description = $post->description;
    $this->editingPostId = $post->id;
    $this->modal('edit-profile')->show();
  }

  public function updatePost(): void {
    if (! auth()->check()) {
        $this->redirect(route('login', absolute: false));
        return;
    }
    $validated = $this->validate([
        'description' => ['required', 'string', 'min:3', 'max:255'],
    ]);
    $post = Post::findOrFail($this->editingPostId);
    $post->update($validated);
    $this->description = '';
    $this->modal('edit-profile')->close();
    $this->dispatch('post-updated'); // Dispatch event for SweetAlert2
  }

  // public function deletePost($id): void {
  //   if (!auth()->check()) {
  //           $this->redirect(route('login', absolute: false));
  //           return;
  //       }
  //       // Dispatch event to trigger confirmation dialog
  //       $this->dispatch('confirm-delete', postId: $id);
  // }
  public function confirmedDelete($id): void {

      if (!auth()->check()) {
          $this->redirect(route('login', absolute: false));
          return;
      }
      Post::findOrFail($id)->delete();
      $this->dispatch('post-deleted');
  }

  public function batchDelete(): void {
    if (! auth()->check()) {
        $this->redirect(route('login', absolute: false));
        return;
    }
    if (!empty($this->postIds)) {
        Post::whereIn('id', $this->postIds)->delete();

        $this->postIds = [];
        $this->js('alertFunction', "Successfully deleted posts.", 'success');
    }
  }

}; ?>

<div>
  <div class="mb-4 gap-2 flex justify-end items-self-end">
    <flux:modal.trigger name="create-profile">
      <flux:button variant="primary">
      Create Post
      </flux:button>
  </flux:modal.trigger>
  <flux:button wire:click="$js.confirmBatchDelete" variant="danger">
      Delete
      </flux:button>
</div>
    <x-manage.layout>
      <table class="w-full p-2 text-center border rounded-xl">
        <thead>
          <tr class="border">
            <th class="border"></th>
            <th class="border">ID</th>
            <th class="border">Description</th>
            <th class="border">Name</th>
            <th class="border">Actions</th>
          </tr>
        </thead>
        <tbody class="border">
          @foreach ($posts as $post)
          <tr class="border" wire:key="post-{{ $post->id }}">
            <td class="border w-1 text-center p-2">
              <flux:checkbox wire:model="postIds" value="{{ $post->id }}"/>
            </td>
              <td class="border p-2">{{
                $post->id
              }}</td>
              <td class="border p-2">{{
                $post->description
              }}</td>
              <td class="border p-2">{{
                $post->user_name
              }}</td>
              <td class="border p-2">
                <flux:button variant="primary" wire:click="editPost({{ $post->id }})" class="m-1">Edit</flux:button>
                
                <flux:button variant="danger" wire:click="$js.deletePost({{ $post->id }})" class="m-1">Delete</flux:button>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
    </x-manage.layout>
    <div class="mt-4">
      {{ $posts->links() }}
    </div>
    
  
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

  <flux:modal name="edit-profile" class="md:w-96">
    <form  wire:submit="updatePost">

      <div class="space-y-6">
        <div>
          <flux:heading size="lg">Update Post</flux:heading>
          <flux:subheading>Update Post.</flux:subheading>
        </div>
        
        <flux:input wire:model="description" :label="__('Description')" type="text" required autofocus autocomplete="description" />
        
        
        <div class="flex">
          <flux:spacer />
          
          <flux:button type="submit" variant="primary">Save changes</flux:button>
        </div>
      </div>
    </form>
  </flux:modal>
  <!-- JavaScript for SweetAlert2 -->
  @script
  <script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('post-created', () => {
            Swal.fire({
                title: 'Success!',
                text: 'Your post has been created.',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false,
            });
        });

        Livewire.on('post-updated', () => {
            Swal.fire({
                title: 'Updated!',
                text: 'Your post has been updated successfully.',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false,
            });
        });
        Livewire.on('post-deleted', () => {
            Swal.fire({
                title: 'Deleted!',
                text: 'Your post has been Deleted successfully.',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false,
            });
        });
        Livewire.on('confirm-delete', (event) => {
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'Do you really want to delete this post? This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, keep it',
                }).then((result) => {
                    if (result.isConfirmed) {
                        alert('Post deleted');
                        Livewire.dispatch('confirmed-delete', { id: event.postId });
                    }
                });
            });
    });

    $js('deletePost', (id) => {
      Swal.fire({
                    title: 'Are you sure?',
                    text: 'Do you really want to delete this post? This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, keep it',
                }).then((result) => {
                    if (result.isConfirmed) {
                      
                      $wire.confirmedDelete(id);
                    }
                });
 
    });
    $js('confirmBatchDelete', () => {
      Swal.fire({
                    title: 'Are you sure?',
                    text: 'Do you really want to delete this post? This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'No, keep it',
                }).then((result) => {
                    if (result.isConfirmed) {
                      
                      $wire.batchDelete();
                    }
                });
 
    });
    $js('alertFunction', (status, stat) => {
      Swal.fire({
                    title: status,
                    icon: stat,
                    timer: 1500,
                    showConfirmButton: false,
                })
 
    });
    
</script>
@endscript
</div>
