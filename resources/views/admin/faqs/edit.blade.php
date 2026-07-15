@extends('layouts.admin')
@section('title', 'Edit FAQ')
@section('subtitle', 'Update question and answer')

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.faqs.index') }}" class="btn-saas btn-saas-ghost btn-saas-sm">
            <i class="bi bi-arrow-left me-1"></i> Back to FAQs
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card-saas">
                <div class="card-saas-header">
                    <h5 class="card-saas-title">FAQ Content</h5>
                </div>
                <div class="card-saas-body">
                    <form action="{{ route('admin.faqs.update', $faq) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-saas-group">
                            <label class="form-saas-label" for="question">Question <span
                                    class="text-danger">*</span></label>
                            <input class="form-saas-input @error('question') is-invalid @enderror" type="text"
                                name="question" id="question" value="{{ old('question', $faq->question) }}" required>
                            @error('question')
                                <div class="form-saas-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-saas-group">
                            <label class="form-saas-label" for="answer">Answer <span class="text-danger">*</span></label>
                            <textarea class="form-saas-textarea @error('answer') is-invalid @enderror" name="answer" id="answer" rows="6"
                                required>{{ old('answer', $faq->answer) }}</textarea>
                            @error('answer')
                                <div class="form-saas-error">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-saas-group mb-0">
                                    <label class="form-saas-label" for="category">Category</label>
                                    <input class="form-saas-input @error('category') is-invalid @enderror" type="text"
                                        name="category" id="category" value="{{ old('category', $faq->category) }}"
                                        placeholder="e.g. Billing, Technical">
                                    @error('category')
                                        <div class="form-saas-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-saas-group mb-0">
                                    <label class="form-saas-label" for="sort_order">Sort Order</label>
                                    <input class="form-saas-input @error('sort_order') is-invalid @enderror" type="number"
                                        name="sort_order" id="sort_order"
                                        value="{{ old('sort_order', $faq->sort_order ?? 0) }}" min="0">
                                    @error('sort_order')
                                        <div class="form-saas-error">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="card-saas-footer mt-4">
                            <div class="d-flex align-items-center gap-3">
                                <button type="submit" class="btn-saas btn-saas-primary">
                                    <i class="bi bi-check-lg me-1"></i> Save Changes
                                </button>
                                <a href="{{ route('admin.faqs.index') }}" class="btn-saas btn-saas-ghost">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card-saas">
                <div class="card-saas-header">
                    <h5 class="card-saas-title">Status</h5>
                </div>
                <div class="card-saas-body">
                    <div class="d-flex align-items-center justify-content-between p-3 rounded-2"
                        style="background:var(--surface-raised)">
                        <div>
                            <div class="fw-semibold" style="font-size:.875rem">Visibility</div>
                            <div style="font-size:.8rem;color:var(--text-muted)">Show on public FAQ page</div>
                        </div>
                        <div class="form-check form-switch mb-0">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                form="faq-status-form" value="1"
                                {{ old('is_active', $faq->is_active) ? 'checked' : '' }}>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-saas mt-3">
                <div class="card-saas-header">
                    <h5 class="card-saas-title">Info</h5>
                </div>
                <div class="card-saas-body">
                    <div class="d-flex flex-column gap-2" style="font-size:.85rem">
                        <div class="d-flex justify-content-between">
                            <span style="color:var(--text-muted)">ID</span>
                            <span class="fw-mono">#{{ $faq->id }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span style="color:var(--text-muted)">Created</span>
                            <span>{{ $faq->created_at->format('d M Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span style="color:var(--text-muted)">Updated</span>
                            <span>{{ $faq->updated_at->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Hidden status form merged on submit --}}
    <form id="faq-status-form" style="display:none"></form>
@endsection
