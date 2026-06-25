@extends('layouts.admin')

@section('title', 'Manage Affiliators')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">
                    <i class="fas fa-users me-2"></i>Affiliators Management
                </h2>
                <button class="btn btn-primary" onclick="showAddModal()">
                    <i class="fas fa-plus me-2"></i>Add New Affiliator
                </button>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-0">Affiliators List</h5>
                </div>
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search affiliators..." id="searchInput">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Referral Code</th>
                            <th>Total Downlines</th>
                            <th>Total Commission</th>
                            <th>Status</th>
                            <th>Joined Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($affiliators as $affiliator)
                        <tr>
                            <td>{{ $affiliator->id }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar bg-primary text-white rounded-circle me-2" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                        {{ substr($affiliator->name, 0, 2) }}
                                    </div>
                                    <strong>{{ $affiliator->name }}</strong>
                                </div>
                            </td>
                            <td>{{ $affiliator->email }}</td>
                            <td><code>{{ $affiliator->referral_code }}</code></td>
                            <td>
                                <span class="badge bg-info">{{ count($affiliator->downlines ?? []) }}</span>
                            </td>
                            <td>Rp {{ number_format($affiliator->total_commission ?? 0, 0, ',', '.') }}</td>
                            <td>
                                @if($affiliator->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($affiliator->created_at)->format('d M Y') }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('admin.affiliators.show', $affiliator->id) }}" class="btn btn-outline-primary" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button class="btn btn-outline-warning" onclick="editAffiliator({{ $affiliator->id }})" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-outline-danger" onclick="deleteAffiliator({{ $affiliator->id }}, '{{ $affiliator->name }}')" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No affiliators found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <p class="text-muted mb-0">Showing {{ $affiliators->firstItem() ?? 0 }} to {{ $affiliators->lastItem() ?? 0 }} of {{ $affiliators->total() }} entries</p>
                <div class="pagination-wrapper">
                    {{ $affiliators->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function showAddModal() {
        Swal.fire({
            title: 'Add New Affiliator',
            html: `
                <div class="text-start">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" id="addName" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" id="addEmail" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" id="addPassword" required>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Create Affiliator',
            cancelButtonText: 'Cancel',
            preConfirm: () => {
                const name = document.getElementById('addName').value;
                const email = document.getElementById('addEmail').value;
                const password = document.getElementById('addPassword').value;
                
                if (!name || !email || !password) {
                    Swal.showValidationMessage('Please fill all fields');
                    return false;
                }
                
                return { name, email, password };
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Implement create logic here
                Swal.fire('Success', 'Affiliator created successfully', 'success');
            }
        });
    }

    function editAffiliator(id) {
        Swal.fire({
            title: 'Edit Affiliator',
            html: `
                <div class="text-start">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" id="editName" value="">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" id="editEmail" value="">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="editIsActive">
                        <label class="form-check-label">Active</label>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Update',
            cancelButtonText: 'Cancel',
            didOpen: () => {
                // Load affiliator data via AJAX
            }
        });
    }

    function deleteAffiliator(id, name) {
        Swal.fire({
            title: 'Are you sure?',
            text: `You are about to delete affiliator "${name}". This action cannot be undone!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Implement delete logic here
                Swal.fire('Deleted!', 'Affiliator has been deleted.', 'success');
            }
        });
    }

    // Search functionality
    document.getElementById('searchInput').addEventListener('keyup', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
</script>
@endpush
