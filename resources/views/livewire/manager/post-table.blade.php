<div class="w-full">
    <table class="w-full p-2 text-center border rounded-xl">
        <thead>
          <tr class="border">
            <th class=""></th>
            <th class="">ID</th>
            <th class="">Description</th>
            <th class="">Name</th>
            <th class="">Actions</th>
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
        <div class="mt-4">
          {{ $posts->links() }}
        </div>
</div>
