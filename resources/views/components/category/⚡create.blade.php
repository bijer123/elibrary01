<?php

use Livewire\Component;
use App\Livewire\Forms\CategoryForm;
use Flux\Flux; // ✅ Tambah ini

new class extends Component
{
    public CategoryForm $form;

    public function save()
    {
        $this->form->store();

        Flux::modal('create-category')->close();

        session()->flash('success', 'Category created successfully');

        $this->redirectRoute('categories.index', navigate: true); // ✅ Fix nama route
    }

    public function resetForm()
    {
        $this->resetValidation();
        $this->form->reset();
    }
};
?>

<div>
    <flux:modal
        name="create-category"
        class="md:w-150"
        x-on:close="$wire.resetForm()"
    >
        <form class="space-y-8" wire:submit.prevent="save">

            {{-- header --}}
            <div class="space-y-2">
                <flux:heading size="lg">
                    Create Category
                </flux:heading>

                <flux:text>
                    Add a new category to your account
                </flux:text>
            </div>

            {{-- form field --}}
            <div class="space-y-6">
                <flux:input
                    label="Name"
                    placeholder="Enter category name"
                    wire:model="form.name"
                />

                <flux:textarea
                    label="Description"
                    placeholder="Enter category description"
                    wire:model="form.description"
                />
            </div>

            {{-- footer --}}
            <div class="flex justify-end gap-3 pt-4">
                <flux:modal.close>
                    <flux:button variant="outline">Cancel</flux:button>
                </flux:modal.close>

                <flux:button type="submit" variant="primary">
                    Create
                </flux:button>
            </div>

        </form>
    </flux:modal>
</div>