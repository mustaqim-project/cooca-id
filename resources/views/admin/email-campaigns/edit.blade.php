@extends('layouts.admin')

@section('title', 'Edit Campaign — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.email-campaigns.index') }}">Campaigns</a>
            <span>/</span>
            <span>Edit</span>
        </div>
        <h1 class="page-title">Edit Email Campaign</h1>
    </div>
</div>

<div class="card" style="max-width: 800px;">
    <div class="card-body">
        <form action="{{ route('admin.email-campaigns.update', $campaign->id ?? 1) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">Email Subject *</label>
                <input type="text" name="subject" class="form-input" required value="{{ old('subject', $campaign->subject ?? '') }}">
            </div>
            <div class="form-group">
                <label class="form-label">HTML Email Content</label>
                <textarea name="content" id="content" class="form-textarea tinymce" rows="10" required>{{ old('content', $campaign->content ?? '') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary w-full mt-4">💾 Save Changes</button>
        </form>
    </div>
</div>
@endsection
