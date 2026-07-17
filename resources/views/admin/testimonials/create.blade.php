@extends('admin.layouts.app')

@section('title', 'Add Testimonial')

@section('content')
    <div class="d-flex flex-column gap-4" style="max-width: 800px; margin: 0 auto;">

        <!-- Header -->
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.testimonials.index') }}"
                class="btn btn-light border-0 rounded-circle p-2 shadow-sm hover-lift"><i class="bi bi-arrow-left"></i></a>
            <div>
                <h2 class="mb-1 fw-bold">Add Testimonial</h2>
                <p class="text-secondary mb-0">Add a new client review or feedback.</p>
            </div>
        </div>

        <!-- Form Card -->
        <div class="card border-0 shadow-sm rounded-4 glass p-4 p-md-5">
            <form action="{{ route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data"
                class="d-flex flex-column gap-4">
                @csrf

                <!-- Image Upload -->
                <div class="d-flex flex-column align-items-center justify-content-center text-center p-4 border rounded-4 border-dashed bg-light-subtle position-relative overflow-hidden hover-lift cursor-pointer"
                    style="transition: all 0.3s ease; border-color: var(--color-border) !important;">
                    <input type="file" name="avatar" id="avatar"
                        class="position-absolute w-100 h-100 opacity-0 cursor-pointer" accept="image/*">
                    <div class="mb-2">
                        <i class="bi bi-person-bounding-box fs-1 text-secondary"></i>
                    </div>
                    <h6 class="fw-medium mb-1">Upload Avatar</h6>
                    <p class="fs-7 text-secondary mb-0">Recommended size: 256x256px (JPG, PNG)</p>
                </div>

                <div class="row g-4">
                    <div class="col-12 col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control rounded-3 shadow-none border bg-transparent"
                                id="name" name="name" placeholder="Name" required>
                            <label for="name">Client Name <span class="text-danger">*</span></label>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control rounded-3 shadow-none border bg-transparent"
                                id="role" name="role" placeholder="Role (e.g. CEO)">
                            <label for="role">Role / Job Title</label>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-floating">
                            <input type="text" class="form-control rounded-3 shadow-none border bg-transparent"
                                id="company" name="company" placeholder="Company Name">
                            <label for="company">Company Name</label>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-floating">
                            <select class="form-select rounded-3 shadow-none border bg-transparent" id="rating"
                                name="rating" required>
                                <option value="5">5 Stars</option>
                                <option value="4">4 Stars</option>
                                <option value="3">3 Stars</option>
                                <option value="2">2 Stars</option>
                                <option value="1">1 Star</option>
                            </select>
                            <label for="rating">Rating <span class="text-danger">*</span></label>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="form-floating">
                            <select class="form-select rounded-3 shadow-none border bg-transparent" id="is_active"
                                name="is_active" required>
                                <option value="1">Active (Featured on Landing Page)</option>
                                <option value="0">Hidden</option>
                            </select>
                            <label for="is_active">Status <span class="text-danger">*</span></label>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-floating">
                            <textarea class="form-control rounded-3 shadow-none border bg-transparent" id="quote" name="quote"
                                placeholder="Quote / Feedback" style="height: 120px" required></textarea>
                            <label for="quote">Quote / Feedback <span class="text-danger">*</span></label>
                        </div>
                    </div>
                </div>

                <hr class="border-light my-2">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.testimonials.index') }}"
                        class="btn btn-light border rounded-pill px-4">Cancel</a>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm hover-lift">
                        <i class="bi bi-check2 me-2"></i> Save Testimonial
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
