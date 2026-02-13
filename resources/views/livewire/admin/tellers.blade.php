<div>
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Manage</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tellers</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <button type="button" class="btn btn-primary px-4 raised d-flex gap-2" wire:click="openModal" wire:loading.attr="disabled">
                <i class="material-icons-outlined" wire:loading.remove wire:target="openModal">add</i>
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" wire:loading wire:target="openModal"></span>
                <span wire:loading.remove wire:target="openModal">Add Teller</span>
                <span wire:loading wire:target="openModal">Loading...</span>
            </button>
        </div>
    </div>
    <!--end breadcrumb-->

    <div class="card rounded-4">
        <div class="card-body">
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="position-relative">
                        <input type="text" class="form-control px-5" wire:model.live.debounce.300ms="search" placeholder="Search tellers...">
                        <span class="material-icons-outlined position-absolute ms-3 translate-middle-y start-0 top-50 fs-5">search</span>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Category</th>
                            <th>Counter</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tellers as $teller)
                            <tr>
                                <td>{{ $teller->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="customer-pic">
                                            <img src="{{ $teller->profile_photo_url ?? asset('assets/images/avatars/11.png') }}" 
                                                 class="rounded-circle" width="40" height="40" alt="">
                                        </div>
                                        <span class="fw-bold">{{ $teller->name }}</span>
                                    </div>
                                </td>
                                <td>{{ $teller->email }}</td>
                                <td>
                                    @if($teller->category)
                                        <span class="badge bg-primary">{{ $teller->category->name }}</span>
                                    @else
                                        <span class="text-secondary">Not assigned</span>
                                    @endif
                                </td>
                                <td>{{ $teller->counter_name ?? '-' }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-sm btn-light px-3 raised d-flex gap-2" wire:click="edit({{ $teller->id }})" wire:loading.attr="disabled">
                                            <i class="material-icons-outlined" wire:loading.remove wire:target="edit({{ $teller->id }})">edit</i>
                                            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" wire:loading wire:target="edit({{ $teller->id }})"></span>
                                            <span wire:loading.remove wire:target="edit({{ $teller->id }})">Edit</span>
                                            <span wire:loading wire:target="edit({{ $teller->id }})">Loading...</span>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger px-3 raised d-flex gap-2" 
                                                onclick="confirmDeleteTeller({{ $teller->id }})"
                                                id="delete-btn-{{ $teller->id }}">
                                            <i class="material-icons-outlined">delete</i>
                                            <span>Delete</span>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <p class="text-secondary mb-0">No tellers found.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $tellers->links() }}
            </div>
        </div>
    </div>

    <!-- Modal -->
    @if($showModal)
    <div class="modal fade show" style="display: block; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 1055; overflow-x: hidden; overflow-y: auto;" tabindex="-1" role="dialog" aria-modal="true">
        <div class="modal-backdrop fade show" style="position: fixed; top: 0; left: 0; z-index: 1054; width: 100vw; height: 100vh; background-color: rgba(0, 0, 0, 0.5);" wire:click="resetForm"></div>
        <div class="modal-dialog modal-dialog-centered" style="position: relative; z-index: 1056; pointer-events: auto;">
            <div class="modal-content" style="pointer-events: auto;">
                <div class="modal-header border-bottom-0 py-2">
                    <h5 class="modal-title">{{ $editingId ? 'Edit Teller' : 'Add Teller' }}</h5>
                    <button type="button" class="btn-close" wire:click="resetForm" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="save">
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" wire:model="name" placeholder="e.g., Maria Santos">
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" wire:model="email" placeholder="e.g., maria@norsu.edu.ph">
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" wire:model="password" placeholder="{{ $editingId ? 'Leave blank to keep current password' : 'Enter password' }}">
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            @if($editingId)
                                <small class="text-secondary">Leave blank to keep current password</small>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select class="form-select @error('category_id') is-invalid @enderror" 
                                    id="category_id" wire:model="category_id">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }} ({{ $category->prefix }})</option>
                                @endforeach
                            </select>
                            @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="counter_name" class="form-label">Counter Name</label>
                            <input type="text" class="form-control @error('counter_name') is-invalid @enderror" 
                                   id="counter_name" wire:model="counter_name" placeholder="e.g., Teller 1, Window 2">
                            @error('counter_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="modal-footer border-top-0">
                            <button type="button" class="btn btn-secondary px-4 raised d-flex gap-2" wire:click="resetForm" wire:loading.attr="disabled">
                                <i class="material-icons-outlined">close</i>
                                <span>Cancel</span>
                            </button>
                            <button type="submit" class="btn btn-primary px-4 raised d-flex gap-2" wire:loading.attr="disabled">
                                <i class="material-icons-outlined" wire:loading.remove wire:target="save">save</i>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" wire:loading wire:target="save"></span>
                                <span wire:loading.remove wire:target="save">Save</span>
                                <span wire:loading wire:target="save">Loading...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    <script>
        // Configure Toastr
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "newestOnTop": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "preventDuplicates": false,
            "onclick": null,
            "showDuration": "300",
            "hideDuration": "1000",
            "timeOut": "3000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        document.addEventListener('livewire:init', () => {
            // Handle Toastr notifications
            Livewire.on('toastr', (event) => {
                const type = event[0].type || 'info';
                const message = event[0].message || '';
                
                switch(type) {
                    case 'success':
                        toastr.success(message);
                        break;
                    case 'error':
                        toastr.error(message);
                        break;
                    case 'warning':
                        toastr.warning(message);
                        break;
                    case 'info':
                    default:
                        toastr.info(message);
                        break;
                }
            });
        });

        // Delete confirmation with SweetAlert2
        function confirmDeleteTeller(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Show loading on delete button
                    const deleteBtn = document.getElementById('delete-btn-' + id);
                    const originalHtml = deleteBtn ? deleteBtn.innerHTML : '';
                    if (deleteBtn) {
                        deleteBtn.disabled = true;
                        deleteBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span>Deleting</span>';
                    }
                    @this.delete(id);
                    // Reset button after a short delay (Livewire will update the DOM)
                    setTimeout(() => {
                        if (deleteBtn && !deleteBtn.disabled) {
                            deleteBtn.innerHTML = '<i class="material-icons-outlined">delete</i><span>Delete</span>';
                        }
                    }, 1000);
                }
            });
        }
    </script>
</div>
