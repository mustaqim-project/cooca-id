@extends('layouts.admin')

@section('title', 'New Email Campaign — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.email-campaigns.index') }}">Email Broadcast</a>
            <span>/</span>
            <span>Create</span>
        </div>
        <h1 class="page-title">New Email Campaign</h1>
    </div>
</div>

<div class="card" style="max-width: 800px;">
    <div class="card-body">
        <form action="{{ route('admin.email-campaigns.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Email Subject *</label>
                <input type="text" name="subject" class="form-input" required value="{{ old('subject') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Target Audience Segment</label>
                <select name="target_audience" class="form-select">
                    <option value="all">All Active Customers</option>
                    <option value="trial">Trial Users</option>
                    <option value="affiliators">Affiliate Partners</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">HTML Email Content</label>
                <textarea name="content" id="content" class="form-textarea tinymce" rows="10" required>{{ old('content') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary w-full mt-4">📧 Create Campaign</button>
        </form>
    </div>
</div>
@endsection
