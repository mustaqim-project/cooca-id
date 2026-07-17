@extends('layouts.admin')

@section('title', 'Add FAQ')
@section('subtitle', 'Create a new frequently asked question')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.faqs.index') }}" class="btn-saas btn-saas-ghost btn-saas-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to FAQs
        </a>
    </div>

    <form action="{{ route('admin.faqs.store') }}" method="POST">
        @csrf
        <div class="row g-4">
            {{-- Main --}}
            <div class="col-lg-8">
                <div class="card-saas">
                    <div class="card-saas-header"><span class="card-saas-title">FAQ Content</span></div>
                    <div class="card-saas-body">

                        <div class="form-saas-group">
                            <label class="form-saas-label" for="question">Question <span
                                    class="text-danger">*</span></label>
                            <input class="form-saas-input @error('question') is-invalid @enderror" type="text"
                                name="question" id="question" value="{{ old('question') }}"
                                placeholder="e.g. How do I reset my password?" required>
                            @error('question')
                                <div class="form-saas-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-saas-group">
                            <label class="form-saas-label" for="answer">Answer <span class="text-danger">*</span></label>
                            <textarea class="form-saas-textarea @error('answer') is-invalid @enderror" name="answer" id="answer" rows="6"
                                placeholder="Provide a clear, helpful answer..." required>{{ old('answer') }}</textarea>
                            @error('answer')
                                <div class="form-saas-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-saas-group">
                            <label class="form-saas-label" for="category">Category (Optional)</label>
                            <input class="form-saas-input @error('category') is-invalid @enderror" type="text"
                                name="category" id="category" value="{{ old('category') }}"
                                placeholder="e.g. Billing, Technical, General">
                            @error('category')
                                <div class="form-saas-error">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">
                <div class="card-saas">
                    <div class="card-saas-header"><span class="card-saas-title">Settings</span></div>
                    <div class="card-saas-body">

                        <div class="form-saas-group">
                            <label class="form-saas-label" for="sort_order">Sort Order</label>
                            <input class="form-saas-input @error('sort_order') is-invalid @enderror" type="number"
                                name="sort_order" id="sort_order" value="{{ old('sort_order', 0) }}" min="0">
                            <div class="form-saas-hint">Lower numbers appear first.</div>
                            @error('sort_order')
                                <div class="form-saas-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                                {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-medium" for="is_active">Active</label>
                            <div class="text-muted" style="font-size:0.82rem">Visible on the public FAQ page.</div>
                        </div>

                        <hr style="border-color:var(--border)">

                        <div class="d-flex flex-column gap-2">
                            <button type="submit" class="btn-saas btn-saas-primary w-100">
                                <i class="bi bi-check-lg me-1"></i> Save FAQ
                            </button>
                            <a href="{{ route('admin.faqs.index') }}" class="btn-saas btn-saas-ghost w-100">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
