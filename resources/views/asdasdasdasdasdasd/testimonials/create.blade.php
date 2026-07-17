@extends('layouts.admin')

@section('title', 'Add Testimonial')
@section('subtitle', 'Create a new customer testimonial')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.testimonials.index') }}" class="btn-saas btn-saas-ghost btn-saas-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to Testimonials
        </a>
    </div>

    <form action="{{ route('admin.testimonials.store') }}" method="POST">
        @csrf
        <div class="row g-4">
            {{-- Main --}}
            <div class="col-lg-8">
                <div class="card-saas">
                    <div class="card-saas-header"><span class="card-saas-title">Testimonial Details</span></div>
                    <div class="card-saas-body">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-saas-group">
                                    <label class="form-saas-label" for="name">Name <span
                                            class="text-danger">*</span></label>
                                    <input class="form-saas-input @error('name') is-invalid @enderror" type="text"
                                        name="name" id="name" value="{{ old('name') }}"
                                        placeholder="Customer name" required>
                                    @error('name')
                                        <div class="form-saas-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-saas-group">
                                    <label class="form-saas-label" for="role">Role / Title</label>
                                    <input class="form-saas-input @error('role') is-invalid @enderror" type="text"
                                        name="role" id="role" value="{{ old('role') }}"
                                        placeholder="e.g. CEO, Marketing Manager">
                                    @error('role')
                                        <div class="form-saas-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-saas-group">
                                    <label class="form-saas-label" for="company">Company</label>
                                    <input class="form-saas-input @error('company') is-invalid @enderror" type="text"
                                        name="company" id="company" value="{{ old('company') }}"
                                        placeholder="Company name">
                                    @error('company')
                                        <div class="form-saas-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-saas-group">
                                    <label class="form-saas-label" for="rating">Rating (1-5)</label>
                                    <input class="form-saas-input @error('rating') is-invalid @enderror" type="number"
                                        name="rating" id="rating" value="{{ old('rating', 5) }}" min="1"
                                        max="5">
                                    @error('rating')
                                        <div class="form-saas-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-saas-group">
                                    <label class="form-saas-label" for="content">Testimonial Content <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-saas-textarea @error('content') is-invalid @enderror" name="content" id="content" rows="5"
                                        placeholder="What the customer said..." required>{{ old('content') }}</textarea>
                                    @error('content')
                                        <div class="form-saas-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-saas-group">
                                    <label class="form-saas-label" for="avatar_url">Avatar URL</label>
                                    <input class="form-saas-input @error('avatar_url') is-invalid @enderror" type="url"
                                        name="avatar_url" id="avatar_url" value="{{ old('avatar_url') }}"
                                        placeholder="https://...">
                                    @error('avatar_url')
                                        <div class="form-saas-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-saas-group">
                                    <label class="form-saas-label" for="sort_order">Sort Order</label>
                                    <input class="form-saas-input @error('sort_order') is-invalid @enderror" type="number"
                                        name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}"
                                        min="0">
                                    <div class="form-saas-hint">Lower numbers appear first.</div>
                                    @error('sort_order')
                                        <div class="form-saas-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                <div class="card-saas">
                    <div class="card-saas-header"><span class="card-saas-title">Settings</span></div>
                    <div class="card-saas-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured"
                                value="1" {{ old('is_featured') ? 'checked' : '' }}>
                            <label class="form-check-label fw-medium" for="is_featured">Featured Testimonial</label>
                            <div class="text-muted" style="font-size:0.82rem">Show prominently on the homepage.</div>
                        </div>

                        <hr style="border-color:var(--border)">

                        <div class="d-flex flex-column gap-2">
                            <button type="submit" class="btn-saas btn-saas-primary w-100">
                                <i class="bi bi-check-lg me-1"></i> Save Testimonial
                            </button>
                            <a href="{{ route('admin.testimonials.index') }}"
                                class="btn-saas btn-saas-ghost w-100">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
