@extends('admin.layouts.app')

@section('title', 'Review Details')

@section('content')
    <div class="d-flex flex-column gap-4">

        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.reviews.index') }}"
                    class="btn btn-light border-0 rounded-circle p-2 shadow-sm hover-lift"><i
                        class="bi bi-arrow-left"></i></a>
                <div>
                    <h2 class="mb-1 fw-bold text-capitalize">Review #101</h2>
                    <p class="text-secondary mb-0">Review details and moderation tools.</p>
                </div>
            </div>
            <div class="d-flex gap-2">
                <form action="{{ route('admin.reviews.approve', $review->id ?? 1) }}" method="POST" class="d-inline-block">
                    @csrf
                    <button type="submit" class="btn btn-success rounded-pill px-4 hover-lift shadow-sm">
                        <i class="bi bi-check-circle me-2"></i> Approve
                    </button>
                </form>
                <form action="{{ route('admin.reviews.reject', $review->id ?? 1) }}" method="POST" class="d-inline-block">
                    @csrf
                    <button type="submit" class="btn btn-warning rounded-pill px-4 hover-lift shadow-sm">
                        <i class="bi bi-x-circle me-2"></i> Reject
                    </button>
                </form>
            </div>
        </div>

        <div class="row g-4">
            <!-- Review Content -->
            <div class="col-12 col-xl-8">
                <div class="card border-0 shadow-sm rounded-4 glass h-100">
                    <div
                        class="card-header bg-transparent border-bottom border-light p-4 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Review Content</h5>
                        <span
                            class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1">Pending
                            Moderation</span>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex flex-column gap-4">

                            <!-- Rating -->
                            <div>
                                <label class="text-secondary fs-7 mb-2 d-block">Rating Given</label>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="text-warning fs-3">
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                        <i class="bi bi-star-fill"></i>
                                    </div>
                                    <span class="fw-bold fs-5">5.0 / 5.0</span>
                                </div>
                            </div>

                            <!-- Review Text -->
                            <div>
                                <label class="text-secondary fs-7 mb-2 d-block">Review Text</label>
                                <div class="p-4 bg-light rounded-4 border fs-5 fst-italic text-dark">
                                    "Sangat membantu operasional bisnis saya. Fiturnya lengkap dan mudah digunakan oleh
                                    karyawan saya yang awam IT sekalipun. Customer service nya juga cepat tanggap."
                                </div>
                            </div>

                            <!-- Attachments (if any) -->
                            <div>
                                <label class="text-secondary fs-7 mb-2 d-block">Attached Media</label>
                                <div class="d-flex gap-3">
                                    <div class="bg-light border rounded-3 d-flex align-items-center justify-content-center text-secondary"
                                        style="width: 100px; height: 100px;">
                                        <i class="bi bi-image fs-3"></i>
                                    </div>
                                    <div class="bg-light border rounded-3 d-flex align-items-center justify-content-center text-secondary"
                                        style="width: 100px; height: 100px;">
                                        <i class="bi bi-image fs-3"></i>
                                    </div>
                                </div>
                            </div>

                            <hr class="border-light my-2">

                            <!-- Reply Section -->
                            <div>
                                <label class="fw-bold mb-3 d-block"><i class="bi bi-reply text-primary me-2"></i> Official
                                    Response</label>
                                <form action="{{ route('admin.reviews.reply', $review->id ?? 1) }}" method="POST">
                                    @csrf
                                    <textarea name="reply" class="form-control bg-light border shadow-none rounded-3 mb-3" rows="4"
                                        placeholder="Write an official response to this review (visible to public)..."></textarea>
                                    <div class="text-end">
                                        <button type="submit"
                                            class="btn btn-primary rounded-pill px-4 shadow-sm hover-lift">Post
                                            Response</button>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Info -->
            <div class="col-12 col-xl-4 d-flex flex-column gap-4">

                <!-- Author Info -->
                <div class="card border-0 shadow-sm rounded-4 glass p-4 text-center">
                    <img src="https://ui-avatars.com/api/?name=Budi+Santoso&background=random"
                        class="rounded-circle mx-auto mb-3 shadow-sm" width="80" height="80" alt="Author">
                    <h4 class="fw-bold mb-1">Budi Santoso</h4>
                    <p class="text-secondary mb-3">budi.santoso@email.com</p>
                    <div>
                        <span class="badge bg-light text-dark border rounded-pill px-3 py-2"><i
                                class="bi bi-patch-check-fill text-primary me-1"></i> Verified Buyer</span>
                    </div>

                    <hr class="border-light my-4">

                    <div class="d-flex justify-content-between text-start mb-3">
                        <span class="text-secondary fs-7">Member Since</span>
                        <span class="fw-medium fs-7">Jan 2025</span>
                    </div>
                    <div class="d-flex justify-content-between text-start">
                        <span class="text-secondary fs-7">Total Reviews Left</span>
                        <span class="fw-medium fs-7">4 Reviews</span>
                    </div>
                </div>

                <!-- Context Info -->
                <div class="card border-0 shadow-sm rounded-4 glass p-4">
                    <h5 class="fw-bold mb-4">Review Context</h5>

                    <div class="mb-4">
                        <label class="text-secondary fs-7 mb-1 d-block">Product Reviewed</label>
                        <div class="d-flex align-items-center gap-2">
                            <div class="bg-primary-subtle text-primary rounded p-2"><i class="bi bi-box-seam"></i></div>
                            <button type="button" class="btn btn-link fw-bold text-decoration-none p-0">Cooca ERP
                                Pro</button>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="text-secondary fs-7 mb-1 d-block">Transaction Ref</label>
                        <span class="fw-medium font-monospace">TRX-2026-902</span>
                    </div>

                    <div class="mb-0">
                        <label class="text-secondary fs-7 mb-1 d-block">Submitted On</label>
                        <div class="fw-medium">Jul 15, 2026 14:30 WIB</div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
