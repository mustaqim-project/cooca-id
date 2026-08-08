@extends('layouts.admin')

@section('title', 'Configure Integration — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.api-integrations.index') }}">Integrations</a>
            <span>/</span>
            <span>Edit</span>
        </div>
        <h1 class="page-title">Configure Integration Keys</h1>
    </div>
</div>

<div class="card" style="max-width: 600px;">
    <div class="card-body">
        <form action="{{ route('admin.api-integrations.update', $provider) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">Status</label>
                <select name="is_active" class="form-select">
                    <option value="1" {{ ($integration->is_active ?? false) ? 'selected' : '' }}>Active / Enabled</option>
                    <option value="0" {{ !($integration->is_active ?? false) ? 'selected' : '' }}>Disabled</option>
                </select>
            </div>
            
            @foreach($schema['fields'] as $key => $meta)
                <div class="form-group">
                    <label class="form-label">{{ $meta['label'] }}</label>
                    
                    @if($meta['type'] === 'boolean')
                        <select name="config[{{ $key }}]" class="form-select">
                            <option value="1" {{ (isset($integration->config[$key]) && $integration->config[$key]) ? 'selected' : '' }}>Yes / Sandbox / Enabled</option>
                            <option value="0" {{ (!isset($integration->config[$key]) || !$integration->config[$key]) ? 'selected' : '' }}>No / Production / Disabled</option>
                        </select>
                    @else
                        <input type="{{ $meta['type'] === 'password' ? 'password' : 'text' }}" 
                               name="config[{{ $key }}]" 
                               class="form-input" 
                               value="{{ $meta['type'] !== 'password' ? ($integration->config[$key] ?? '') : '' }}"
                               placeholder="{{ $meta['type'] === 'password' ? '(Leave blank to keep existing key)' : '' }}">
                    @endif
                </div>
            @endforeach

            <button type="submit" class="btn btn-primary w-full mt-4">💾 Save Credentials</button>
        </form>
    </div>
</div>
@endsection
