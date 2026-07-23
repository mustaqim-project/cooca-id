@extends('customer.layouts.app')

@section('title', 'Create Ticket')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-xl-8">
            <!-- Header -->
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('customer.tickets.index') }}" class="btn btn-sm btn-light border rounded-pill px-3 hover-lift me-3">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
                <div>
                    <h2 class="mb-1 fw-bold">Create Ticket</h2>
                    <p class="text-secondary mb-0">Submit a new support request.</p>
                </div>
            </div>

            <!-- Form Card -->
            <div class="card border-0 shadow-sm rounded-4 glass overflow-hidden">
                <div class="card-header bg-transparent border-bottom border-light p-4">
                    <h5 class="mb-0 fw-semibold"><i class="bi bi-pencil-square me-2"></i> Ticket Details</h5>
                </div>
                
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('customer.tickets.store') }}" method="POST">
                        @csrf
                        
                        <!-- Subject -->
                        <div class="mb-4">
                            <label class="form-label fw-medium text-secondary">Subject <span class="text-danger">*</span></label>
                            <input type="text" name="subject" value="{{ old('subject') }}" class="form-control bg-light border-light py-2" placeholder="Brief description of the issue" required>
                        </div>
                        
                        <!-- Priority -->
                        <div class="mb-4">
                            <label class="form-label fw-medium text-secondary">Priority</label>
                            <select name="priority" class="form-select bg-light border-light py-2 text-secondary">
                                <option value="low" @selected(old('priority') === 'low')>Low</option>
                                <option value="medium" @selected(old('priority', 'medium') === 'medium')>Medium</option>
                                <option value="high" @selected(old('priority') === 'high')>High</option>
                            </select>
                            <div class="form-text mt-2 text-secondary fs-7">Select high priority only for critical system issues.</div>
                        </div>
                        
                        <!-- Message -->
                        <div class="mb-4">
                            <label class="form-label fw-medium text-secondary">Message <span class="text-danger">*</span></label>
                            <textarea name="message" rows="6" class="form-control bg-light border-light py-2" placeholder="Provide detailed information about your issue..." required>{{ old('message') }}</textarea>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-5 pt-3 border-top border-light">
                            <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 hover-lift fw-medium">
                                Submit Ticket <i class="bi bi-send ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
