@extends('admin.layouts.app')

@section('title', 'Edit FAQ')

@section('content')
    <div class="d-flex flex-column gap-4" style="max-width: 800px; margin: 0 auto;">

        <!-- Header -->
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.faqs.index') }}"
                class="btn btn-light border-0 rounded-circle p-2 shadow-sm hover-lift"><i class="bi bi-arrow-left"></i></a>
            <div>
                <h2 class="mb-1 fw-bold">Edit Question</h2>
                <p class="text-secondary mb-0">Update the details for this frequently asked question.</p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="card border-0 shadow-sm rounded-4 glass p-4 p-md-5">
            <form action="{{ route('admin.faqs.update', $faq->id ?? 1) }}" method="POST" class="d-flex flex-column gap-4">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <div class="col-12">
                        <div class="form-floating">
                            <input type="text" class="form-control rounded-3 shadow-none border bg-transparent"
                                id="question" name="question" placeholder="Question"
                                value="{{ $faq->question ?? 'How do I upgrade my license tier?' }}" required>
                            <label for="question">Question</label>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-floating">
                            <select class="form-select rounded-3 shadow-none border bg-transparent" id="category"
                                name="category" required>
                                <option value="">Select Category</option>
                                <option value="General" {{ ($faq->category ?? '') == 'General' ? 'selected' : '' }}>General
                                </option>
                                <option value="Billing" {{ ($faq->category ?? 'Billing') == 'Billing' ? 'selected' : '' }}>
                                    Billing</option>
                                <option value="Technical" {{ ($faq->category ?? '') == 'Technical' ? 'selected' : '' }}>
                                    Technical</option>
                                <option value="Licensing" {{ ($faq->category ?? '') == 'Licensing' ? 'selected' : '' }}>
                                    Licensing</option>
                            </select>
                            <label for="category">Category</label>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-floating">
                            <select class="form-select rounded-3 shadow-none border bg-transparent" id="is_published"
                                name="is_published" required>
                                <option value="1" {{ $faq->is_published ?? true ? 'selected' : '' }}>Published
                                    (Visible to Users)</option>
                                <option value="0" {{ !($faq->is_published ?? true) ? 'selected' : '' }}>Draft (Hidden)
                                </option>
                            </select>
                            <label for="is_published">Visibility Status</label>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label text-secondary fw-medium mb-2">Answer (HTML Supported)</label>
                            <textarea class="form-control rounded-3 shadow-none border bg-transparent" id="answer" name="answer"
                                placeholder="Provide a detailed answer..." style="height: 200px" required>{{ $faq->answer ?? 'To upgrade your license tier, go to Settings > Billing and click on Change Plan. Select the tier you want and proceed to checkout.' }}</textarea>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-floating">
                            <input type="number" class="form-control rounded-3 shadow-none border bg-transparent"
                                id="order" name="order" value="{{ $faq->order ?? 0 }}" placeholder="Display Order">
                            <label for="order">Display Order (Lower number = Higher position)</label>
                        </div>
                    </div>
                </div>

                <hr class="border-light my-2">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.faqs.index') }}" class="btn btn-light border rounded-pill px-4">Cancel</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm hover-lift">
                        <i class="bi bi-check2 me-2"></i> Update Question
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
