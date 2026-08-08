@extends('layouts.admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.finance.index') }}">Finance</a>
            <span>/</span>
            <span>Chart of Accounts</span>
        </div>
        <h1 class="page-title">Chart of Accounts</h1>
        <p class="page-subtitle">Kelola daftar akun untuk pembukuan jurnal (Double-Entry).</p>
    </div>
    <div class="page-actions">
        <button onclick="document.getElementById('addCoaModal').classList.remove('hidden')" class="btn btn-primary">
            <i class="fa-solid fa-plus" style="margin-right: 6px;"></i> Tambah Akun
        </button>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Kode Akun</th>
                        <th>Nama Akun</th>
                        <th>Tipe</th>
                        <th>Sub Tipe</th>
                        <th>Status</th>
                    </tr>
                </thead>
            <tbody>
                @forelse($accounts as $acc)
                    <tr>
                        <td style="font-family: monospace;">{{ $acc->code }}</td>
                        <td style="font-weight: 500;">{{ $acc->name }}</td>
                        <td class="text-muted">{{ $acc->typeAccount->name ?? '-' }}</td>
                        <td class="text-muted">{{ $acc->subTypeAccount->name ?? '-' }}</td>
                        <td>
                            @if($acc->is_enabled)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-danger">Nonaktif</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 2rem; color: var(--text-muted);">
                            <i class="fa-solid fa-folder-open" style="font-size: 2rem; margin-bottom: 0.5rem; opacity: 0.5;"></i>
                            <p>Belum ada Chart of Account terdaftar.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>

<!-- Modal Add -->
<div id="addCoaModal" class="hidden fixed inset-0 z-[100]" style="display: none; align-items: center; justify-content: center; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px);">
    <div style="background: var(--surface); border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); width: 100%; max-width: 450px; margin: 16px; border: 1px solid var(--border); overflow: hidden;">
        <form action="{{ route('admin.accounting.coa.store') }}" method="POST">
            @csrf
            <div style="padding: 16px 24px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; background: var(--background);">
                <h3 style="font-size: 1.125rem; font-weight: bold; margin: 0; color: var(--text);">Tambah Chart of Account</h3>
                <button type="button" onclick="document.getElementById('addCoaModal').style.display='none'" class="btn btn-ghost btn-sm" style="padding: 4px 8px;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div style="padding: 24px; display: flex; flex-direction: column; gap: 16px;">
                <div>
                    <label class="form-label">Kode Akun</label>
                    <input type="number" name="code" required class="form-control">
                </div>
                <div>
                    <label class="form-label">Nama Akun</label>
                    <input type="text" name="name" required class="form-control">
                </div>
                <div>
                    <label class="form-label">Deskripsi</label>
                    <textarea name="description" rows="2" class="form-control"></textarea>
                </div>
            </div>
            <div style="padding: 16px 24px; border-top: 1px solid var(--border); background: var(--background); display: flex; justify-content: flex-end; gap: 12px;">
                <button type="button" onclick="document.getElementById('addCoaModal').style.display='none'" class="btn btn-ghost">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Akun</button>
            </div>
        </form>
    </div>
</div>
<script>
    // Adjust open modal script
    document.querySelector('button[onclick*="addCoaModal"]').onclick = function() {
        document.getElementById('addCoaModal').style.display = 'flex';
        document.getElementById('addCoaModal').classList.remove('hidden');
    }
</script>
@endsection
