<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-zinc-800 dark:text-white">Books</h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400">Manage your library books collection</p>
        </div>
        <flux:button wire:click="create" variant="primary" icon="plus">Add Book</flux:button>
    </div>

    <flux:table :paginate="$books">
        <flux:table.columns>
            <flux:table.column>ISBN</flux:table.column>
            <flux:table.column>Title</flux:table.column>
            <flux:table.column>Author</flux:table.column>
            <flux:table.column>Category</flux:table.column>
            <flux:table.column>Stock</flux:table.column>
            <flux:table.column align="end">Action</flux:table.column>
        </flux:table.columns>

        <flux:table.rows>
            @forelse ($books as $book)
                <flux:table.row :key="$book->id">
                    <flux:table.cell class="font-mono text-xs">{{ $book->isbn }}</flux:table.cell>
                    <flux:table.cell class="font-medium">{{ $book->title }}</flux:table.cell>
                    <flux:table.cell>{{ $book->author }}</flux:table.cell>
                    <flux:table.cell>
                        <flux:badge size="sm" color="zinc" inset="top bottom">{{ $book->category?->name ?? 'Uncategorized' }}</flux:badge>
                    </flux:table.cell>
                    <flux:table.cell>{{ $book->stock }} pcs</flux:table.cell>
                    <flux:table.cell align="end">
                        <flux:dropdown>
                            <flux:button variant="ghost" icon="ellipsis-horizontal" size="sm" />
                            <flux:menu>
                                <flux:menu.item wire:click="edit({{ $book->id }})" icon="pencil">Edit</flux:menu.item>
                                <flux:menu.item wire:click="delete({{ $book->id }})" wire:confirm="Are you sure you want to delete this book?" icon="trash" variant="danger">Delete</flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                    </flux:table.cell>
                </flux:table.row>
            @empty
                <flux:table.row>
                    <flux:table.cell colspan="6" class="text-center py-8 text-zinc-400">No books found.</flux:table.cell>
                </flux:table.row>
            @endforelse
        </flux:table.rows>
    </flux:table>

    <flux:modal wire:model="showModal" class="md:w-[32rem]">
        <form wire:submit.prevent="save" class="space-y-6">
            <div>
                <flux:heading size="lg">{{ $editingBookId ? 'Edit Book' : 'Add New Book' }}</flux:heading>
                <flux:subheading>Fill in the book details correctly.</flux:subheading>
            </div>

            <div class="space-y-4">
                <flux:input wire:model="isbn" label="ISBN Code" placeholder="e.g. 978-602-8512" />
                <flux:input wire:model="title" label="Book Title" placeholder="e.g. Struktur Data" />
                <flux:input wire:model="author" label="Author / Penulis" placeholder="e.g. Prof. Budi" />
                
                <flux:select wire:model="category_id" label="Category">
                    <option value="">Select a category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </flux:select>

                <flux:input wire:model="stock" type="number" label="Total Stock" placeholder="e.g. 10" />
            </div>

            <div class="flex gap-2 justify-end">
                <flux:modal.close><flux:button variant="ghost">Cancel</flux:button></flux:modal.close>
                <flux:button type="submit" variant="primary">Save Book</flux:button>
            </div>
        </form>
    </flux:modal>
</div>