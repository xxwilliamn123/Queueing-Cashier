<?php

namespace App\Livewire\Admin;

use App\Models\TellerCategory;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.app')]
class Tellers extends Component
{
    use WithPagination;

    public $showModal = false;
    public $editingId = null;
    public $name = '';
    public $email = '';
    public $password = '';
    public $category_id = '';
    public $counter_name = '';
    public $search = '';

    protected $paginationTheme = 'bootstrap';

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8',
        'category_id' => 'required|exists:teller_categories,id',
        'counter_name' => 'required|string|max:255',
    ];

    public function mount()
    {
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->category_id = '';
        $this->counter_name = '';
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
        $teller = User::findOrFail($id);
        $this->editingId = $teller->id;
        $this->name = $teller->name;
        $this->email = $teller->email;
        $this->password = '';
        $this->category_id = $teller->category_id;
        $this->counter_name = $teller->counter_name;
        $this->showModal = true;
    }

    public function save()
    {
        $rules = $this->rules;
        if ($this->editingId) {
            $rules['email'] = 'required|email|unique:users,email,' . $this->editingId;
            $rules['password'] = 'nullable|min:8';
        }

        $this->validate($rules);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => 'teller',
            'category_id' => $this->category_id,
            'counter_name' => $this->counter_name,
        ];

        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->editingId) {
            $teller = User::findOrFail($this->editingId);
            $teller->update($data);
            $this->dispatch('toastr', [
                'type' => 'success',
                'message' => 'Teller updated successfully!',
            ]);
        } else {
            User::create($data);
            $this->dispatch('toastr', [
                'type' => 'success',
                'message' => 'Teller created successfully!',
            ]);
        }

        $this->resetForm();
    }

    public function delete($id)
    {
        $teller = User::findOrFail($id);
        
        // Check if teller has tickets
        if ($teller->tickets()->count() > 0) {
            $this->dispatch('toastr', [
                'type' => 'error',
                'message' => 'Cannot delete teller with associated tickets!',
            ]);
            return;
        }

        $teller->delete();
        $this->dispatch('toastr', [
            'type' => 'success',
            'message' => 'Teller deleted successfully!',
        ]);
    }

    public function render()
    {
        $query = User::where('role', 'teller')->with('category');

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('counter_name', 'like', '%' . $this->search . '%');
            });
        }

        $tellers = $query->orderBy('name')->paginate(10);
        $categories = TellerCategory::orderBy('name')->get();

        return view('livewire.admin.tellers', [
            'tellers' => $tellers,
            'categories' => $categories,
        ]);
    }
}
