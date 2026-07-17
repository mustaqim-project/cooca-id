@extends('admin.layouts.app')

@section('title', 'Create Email Campaign')

@section('content')
    <div class="d-flex flex-column gap-4" style="max-width: 1000px; margin: 0 auto;">

        <!-- Header -->
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.email-campaigns.index') }}"
                class="btn btn-light border-0 rounded-circle p-2 shadow-sm hover-lift"><i class="bi bi-arrow-left"></i></a>
            <div>
                <h2 class="mb-1 fw-bold text-capitalize">Create Email Campaign</h2>
                <p class="text-secondary mb-0">Set up targeting, content, and scheduling for your campaign.</p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="card border-0 shadow-sm rounded-4 glass p-4 p-md-5">
            <form action="{{ route('admin.email-campaigns.store') }}" method="POST" class="d-flex flex-column gap-4">
                @csrf

                <!-- Section 1: Basic Details -->
                <div>
                    <h5 class="fw-bold mb-3"><i class="bi bi-info-circle me-2 text-primary"></i> 1. Campaign Details</h5>
                    <div class="row g-4">
                        <div class="col-12 col-md-8">
                            <div class="form-floating">
                                <input type="text"
                                    class="form-control rounded-3 shadow-none border bg-transparent @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}" placeholder="Campaign Name"
                                    required>
                                <label for="name">Campaign Name (Internal) <span class="text-danger">*</span></label>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12 col-md-4">
                            <div class="form-floating">
                                <select
                                    class="form-select rounded-3 shadow-none border bg-transparent @error('status') is-invalid @enderror"
                                    id="status" name="status" required>
                                    <option value="Draft" {{ old('status') == 'Draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="Scheduled" {{ old('status') == 'Scheduled' ? 'selected' : '' }}>Scheduled
                                    </option>
                                    <option value="Send Now" {{ old('status') == 'Send Now' ? 'selected' : '' }}>Send Now
                                    </option>
                                </select>
                                <label for="status">Initial Status</label>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-floating">
                                <input type="text"
                                    class="form-control rounded-3 shadow-none border bg-transparent @error('subject') is-invalid @enderror"
                                    id="subject" name="subject" value="{{ old('subject') }}" placeholder="Email Subject"
                                    required>
                                <label for="subject">Email Subject Line <span class="text-danger">*</span></label>
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="border-light my-2">

                <!-- Section 2: Targeting -->
                <div>
                    <h5 class="fw-bold mb-3"><i class="bi bi-people me-2 text-primary"></i> 2. Target Audience</h5>
                    <div class="row g-4">
                        <div class="col-12 col-md-6">
                            <div class="form-floating">
                                <select
                                    class="form-select rounded-3 shadow-none border bg-transparent @error('target_segment') is-invalid @enderror"
                                    id="target_segment" name="target_segment" required>
                                    <option value="" disabled selected>Select an audience segment...</option>
                                    <option value="all_customers"
                                        {{ old('target_segment') == 'all_customers' ? 'selected' : '' }}>All Customers
                                        (Approx. 4,500)</option>
                                    <option value="active_subscribers"
                                        {{ old('target_segment') == 'active_subscribers' ? 'selected' : '' }}>Active
                                        Subscribers (Approx. 3,200)</option>
                                    <option value="inactive_customers"
                                        {{ old('target_segment') == 'inactive_customers' ? 'selected' : '' }}>Inactive
                                        Customers (Approx. 1,300)</option>
                                    <option value="affiliators"
                                        {{ old('target_segment') == 'affiliators' ? 'selected' : '' }}>Affiliators (Approx.
                                        450)</option>
                                    <option value="custom" {{ old('target_segment') == 'custom' ? 'selected' : '' }}>Custom
                                        List...</option>
                                </select>
                                <label for="target_segment">Recipient Segment <span class="text-danger">*</span></label>
                                @error('target_segment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div class="form-floating">
                                <input type="datetime-local"
                                    class="form-control rounded-3 shadow-none border bg-transparent @error('scheduled_at') is-invalid @enderror"
                                    id="scheduled_at" name="scheduled_at" value="{{ old('scheduled_at') }}">
                                <label for="scheduled_at">Schedule Delivery (Optional)</label>
                                <div class="form-text fs-7">Leave blank if sending immediately.</div>
                                @error('scheduled_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="border-light my-2">

                <!-- Section 3: Content -->
                <div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0"><i class="bi bi-envelope-paper me-2 text-primary"></i> 3. Email Content
                        </h5>
                        <a href="#" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                            <i class="bi bi-layout-text-window me-1"></i> Choose Template
                        </a>
                    </div>

                    <div class="row g-4">
                        <div class="col-12">
                            <div class="border rounded-3 p-2 bg-light">
                                <!-- Toolbar mock -->
                                <div class="border-bottom pb-2 mb-2 d-flex flex-wrap gap-2 text-secondary">
                                    <i class="bi bi-type-bold px-2 py-1 rounded hover-bg-light cursor-pointer"></i>
                                    <i class="bi bi-type-italic px-2 py-1 rounded hover-bg-light cursor-pointer"></i>
                                    <i class="bi bi-type-underline px-2 py-1 rounded hover-bg-light cursor-pointer"></i>
                                    <div class="vr"></div>
                                    <i class="bi bi-justify-left px-2 py-1 rounded hover-bg-light cursor-pointer"></i>
                                    <i class="bi bi-justify px-2 py-1 rounded hover-bg-light cursor-pointer"></i>
                                    <i class="bi bi-justify-right px-2 py-1 rounded hover-bg-light cursor-pointer"></i>
                                    <div class="vr"></div>
                                    <i class="bi bi-link-45deg px-2 py-1 rounded hover-bg-light cursor-pointer"></i>
                                    <i class="bi bi-image px-2 py-1 rounded hover-bg-light cursor-pointer"></i>
                                    <div class="vr"></div>
                                    <i class="bi bi-code-slash px-2 py-1 rounded hover-bg-light cursor-pointer"></i>
                                    <button type="button" class="btn btn-sm btn-light border ms-auto py-0 px-2 fs-8">Merge
                                        Tags <i class="bi bi-chevron-down ms-1"></i></button>
                                </div>
                                <textarea class="form-control border-0 shadow-none bg-transparent @error('content') is-invalid @enderror"
                                    id="content" name="content"
                                    placeholder="Design your email content here. Use {name} for personalized greetings..."
                                    style="min-height: 400px; resize: vertical;" required>{{ old('content') }}</textarea>
                            </div>
                            @error('content')
                                <div class="text-danger fs-7 mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr class="border-light my-2 mt-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.email-campaigns.index') }}"
                        class="btn btn-light border rounded-pill px-4">Cancel</a>
                    <button type="button" class="btn btn-outline-primary rounded-pill px-4 shadow-sm hover-lift">
                        <i class="bi bi-send-check me-2"></i> Send Test
                    </button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm hover-lift">
                        <i class="bi bi-check2 me-2"></i> Save Campaign
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
