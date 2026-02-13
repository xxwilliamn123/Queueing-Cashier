<?php

namespace App\Livewire\Admin;

use App\Models\TellerCategory;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Categories extends Component
{
    use WithPagination;

    public $showModal = false;
    public $editingId = null;
    public $name = '';
    public $prefix = '';
    public $search = '';

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'name' => 'required|string|max:255',
        'prefix' => 'required|string|max:10|unique:teller_categories,prefix',
    ];

    public function mount()
    {
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->name = '';
        $this->prefix = '';
        $this->showModal = false;
        $this->resetValidation();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit($id)
    {
        $category = TellerCategory::findOrFail($id);
        $this->editingId = $category->id;
        $this->name = $category->name;
        $this->prefix = $category->prefix;
        $this->showModal = true;
    }

    public function save()
    {
        $rules = $this->rules;
        if ($this->editingId) {
            $rules['prefix'] = 'required|string|max:10|unique:teller_categories,prefix,' . $this->editingId;
        }

        $this->validate($rules);

        if ($this->editingId) {
            $category = TellerCategory::findOrFail($this->editingId);
            $category->update([
                'name' => $this->name,
                'prefix' => strtoupper($this->prefix),
            ]);
            $this->dispatch('toastr', [
                'type' => 'success',
                'message' => 'Category updated successfully!',
            ]);
        } else {
            TellerCategory::create([
                'name' => $this->name,
                'prefix' => strtoupper($this->prefix),
            ]);
            $this->dispatch('toastr', [
                'type' => 'success',
                'message' => 'Category created successfully!',
            ]);
        }

        $this->resetForm();
    }

    public function delete($id)
    {
        $category = TellerCategory::findOrFail($id);
        
        // Check if category has tickets or tellers
        if ($category->tickets()->count() > 0 || $category->users()->count() > 0) {
            $this->dispatch('toastr', [
                'type' => 'error',
                'message' => 'Cannot delete category with associated tickets or tellers!',
            ]);
            return;
        }

        $category->delete();
        $this->dispatch('toastr', [
            'type' => 'success',
            'message' => 'Category deleted successfully!',
        ]);
    }

    public function render()
    {
        $query = TellerCategory::query();

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('prefix', 'like', '%' . $this->search . '%');
            });
        }

        $categories = $query->orderBy('name')->paginate(10);

        return view('livewire.admin.categories', [
            'categories' => $categories,
        ]);
    }
}
