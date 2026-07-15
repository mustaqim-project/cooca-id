@extends('layouts.admin')
@section('title', 'Affiliators')
@section('subtitle', 'Manage affiliate partners')
@section('content')
    <div class="page-toolbar mb-4">
        <div class="page-toolbar-left">
            <div class="input-group" style="width:300px">
                <span class="input-group-text"><i class="bi bi-search"></i></span>
                <input type="text" class="form-control" id="searchInput" placeholder="Search affiliators...">
            </div>
        </div>
        <div class="page-toolbar-right">
            <a href="{{ route('admin.affiliators.create') }}" class="btn-saas btn-saas-primary">
                <i class="bi bi-plus-lg me-1"></i> Add Affiliator
            </a>
        </div>
    </div>
    <div class="card-saas">
        <div class="card-saas-body p-0">
            <div class="table-responsive">
                <table class="table-saas" id="affiliatorsTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Referral Code</th>
                            <th>Downlines</th>
                            <th>Commission</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($affiliators as $affiliator)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div
                                            style="width:32px;height:32px;border-radius:50%;background:var(--primary);display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:700;color:#fff;flex-shrink:0">
                                            {{ strtoupper(substr($affiliator->name, 0, 1)) }}
                                        </div>
                                        <a href="{{ route('admin.affiliators.show', $affiliator) }}"
                                            style="font-weight:500;color:var(--text-primary);text-decoration:none">{{ $affiliator->name }}</a>
                                    </div>
                                </td>
                                <td style="color:var(--text-muted)">{{ $affiliator->email }}</td>
                                <td><code style="font-size:.8rem">{{ $affiliator->referral_code }}</code></td>
                                <td>{{ $affiliator->downlines_count ?? $affiliator->downlines()->count() }}</td>
                                <td>Rp {{ number_format($affiliator->total_commission ?? 0, 0, ',', '.') }}</td>
                                <td>
                                    @if ($affiliator->is_active)
                                        <span class="badge-saas badge-saas-success">Active</span>
                                    @else
                                        <span class="badge-saas badge-saas-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="{{ route('admin.affiliators.show', $affiliator) }}"
                                            class="btn-saas btn-saas-ghost btn-saas-sm btn-saas-icon" title="View">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.affiliators.edit', $affiliator) }}"
                                            class="btn-saas btn-saas-ghost btn-saas-sm btn-saas-icon" title="Edit">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('admin.affiliators.destroy', $affiliator) }}" method="POST"
                                            class="form-confirm-delete">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-saas btn-saas-ghost btn-saas-sm btn-saas-icon"
                                                title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty-state">
                                        <div class="empty-state-icon"><i class="bi bi-people"></i></div>
                                        <div class="empty-state-title">No affiliators found</div>
                                        <div class="empty-state-description">Add your first affiliate partner to get started
                                        </div>
                                        <a href="{{ route('admin.affiliators.create') }}"
                                            class="btn-saas btn-saas-primary mt-3">
                                            <i class="bi bi-plus-lg me-1"></i> Add Affiliator
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if (isset($affiliators) && method_exists($affiliators, 'links'))
            <div class="card-saas-footer">
                {{ $affiliators->links() }}
            </div>
        @endif
    </div>
@endsection
@push('scripts')
    <script>
        document.getElementById('searchInput').addEventListener('input', function() {
            const q = this.value.toLowerCase();
            document.querySelectorAll('#affiliatorsTable tbody tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(q) ? '' : 'none';
            });
        });
    </script>
@endpush
