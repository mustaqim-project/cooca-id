@extends('layouts.admin')

@section('title', 'Profil & Pengaturan Keamanan Admin — COOCA.ID')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <span>Profil & Keamanan</span>
        </div>
        <h1 class="page-title">Profil & Pengaturan Keamanan</h1>
    </div>
</div>

@if ($errors->any())
    <div style="background: var(--danger-soft); color: var(--danger); padding: 14px 18px; border-radius: var(--radius-sm); border: 1px solid var(--danger); margin-bottom: 24px;">
        <div style="font-weight: 700; margin-bottom: 6px;"><i class="fa-solid fa-triangle-exclamation me-1"></i> Terdapat kesalahan input:</div>
        <ul style="margin: 0; padding-left: 20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 24px; max-width: 1000px;">
    {{-- Profile Details --}}
    <div class="card">
        <div class="card-header" style="border-bottom: 1px solid var(--border); padding-bottom: 14px; margin-bottom: 16px;">
            <h3 class="card-title" style="font-size: 16px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-user-pen" style="color: var(--primary);"></i> Informsi Profil Admin
            </h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group mb-3">
                    <label class="form-label" style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Nama Admin</label>
                    <input type="text" name="name" class="form-input" value="{{ old('name', auth('admin')->user()->name ?? 'Administrator') }}" required style="width: 100%; padding: 8px 12px; border-radius: var(--radius-sm); border: 1px solid var(--border); background: var(--bg-secondary); color: var(--text);">
                </div>
                <div class="form-group mb-3">
                    <label class="form-label" style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Alamat Email</label>
                    <input type="email" name="email" class="form-input" value="{{ old('email', auth('admin')->user()->email ?? 'admin@cooca.id') }}" required style="width: 100%; padding: 8px 12px; border-radius: var(--radius-sm); border: 1px solid var(--border); background: var(--bg-secondary); color: var(--text);">
                </div>
                <button type="submit" class="btn btn-primary w-full mt-3" style="width: 100%; padding: 10px; border-radius: var(--radius-sm); background: var(--primary); color: #fff; font-weight: 600; border: none; cursor: pointer;">
                    <i class="fa-solid fa-floppy-disk me-1"></i> Simpan Perubahan Profil
                </button>
            </form>
        </div>
    </div>

    {{-- Change Password --}}
    <div class="card">
        <div class="card-header" style="border-bottom: 1px solid var(--border); padding-bottom: 14px; margin-bottom: 16px;">
            <h3 class="card-title" style="font-size: 16px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-key" style="color: var(--warning);"></i> Ganti Password
            </h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.profile.password.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group mb-3">
                    <label class="form-label" style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Password Saat Ini</label>
                    <input type="password" name="current_password" class="form-input" required style="width: 100%; padding: 8px 12px; border-radius: var(--radius-sm); border: 1px solid var(--border); background: var(--bg-secondary); color: var(--text);">
                </div>
                <div class="form-group mb-3">
                    <label class="form-label" style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Password Baru</label>
                    <input type="password" name="password" class="form-input" minlength="8" required style="width: 100%; padding: 8px 12px; border-radius: var(--radius-sm); border: 1px solid var(--border); background: var(--bg-secondary); color: var(--text);">
                </div>
                <div class="form-group mb-3">
                    <label class="form-label" style="display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px;">Konfirmasi Password Baru</label>
                    <input type="password" name="password_confirmation" class="form-input" minlength="8" required style="width: 100%; padding: 8px 12px; border-radius: var(--radius-sm); border: 1px solid var(--border); background: var(--bg-secondary); color: var(--text);">
                </div>
                <button type="submit" class="btn btn-primary w-full mt-3" style="width: 100%; padding: 10px; border-radius: var(--radius-sm); background: var(--primary); color: #fff; font-weight: 600; border: none; cursor: pointer;">
                    <i class="fa-solid fa-lock me-1"></i> Perbarui Password
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
