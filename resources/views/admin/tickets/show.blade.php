@extends('admin.layouts.app')

@section('title', 'Ticket Reply')

@section('content')
    <div class="d-flex flex-column gap-4">

        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.tickets.index') }}"
                    class="btn btn-light border-0 rounded-circle p-2 shadow-sm hover-lift"><i
                        class="bi bi-arrow-left"></i></a>
                <div>
                    <h2 class="mb-1 fw-bold text-primary">TCK-2026-1042</h2>
                    <p class="text-secondary mb-0">Cannot activate POS license after renewal</p>
                </div>
            </div>
            <div class="d-flex gap-2">
                <form action="{{ route('admin.tickets.resolve', $ticket->id ?? 1) }}" method="POST" class="d-inline-block">
                    @csrf
                    <button type="submit" class="btn btn-success rounded-pill px-4 hover-lift shadow-sm">
                        <i class="bi bi-check2-circle me-2"></i> Mark as Resolved
                    </button>
                </form>
                <form action="{{ route('admin.tickets.close', $ticket->id ?? 1) }}" method="POST" class="d-inline-block"
                    onsubmit="return confirm('Close this ticket?');">
                    @csrf
                    <button type="submit"
                        class="btn btn-light text-secondary bg-white border shadow-sm rounded-pill px-3 hover-lift">
                        <i class="bi bi-x-circle"></i>
                    </button>
                </form>
            </div>
        </div>

        <div class="row g-4">
            <!-- Sidebar Info -->
            <div class="col-12 col-xl-4 order-xl-2 d-flex flex-column gap-4">
                <!-- Ticket Info -->
                <div class="card border-0 shadow-sm rounded-4 glass p-4">
                    <h5 class="fw-bold mb-4">Ticket Details</h5>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-secondary fs-7">Status</span>
                        <span
                            class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1">In
                            Progress</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-secondary fs-7">Priority</span>
                        <span
                            class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1">High</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-secondary fs-7">Category</span>
                        <span class="fw-medium">License / Billing</span>
                    </div>

                    <hr class="border-light my-3">

                    <div class="mb-3">
                        <label class="text-secondary fs-7 mb-1 d-block">Created On</label>
                        <div class="fw-medium fs-7">Jul 14, 2026 (2 days ago)</div>
                    </div>
                    <div>
                        <label class="text-secondary fs-7 mb-1 d-block">Assigned To</label>
                        <div class="d-flex align-items-center gap-2">
                            <img src="https://ui-avatars.com/api/?name=Admin+User&background=0D8ABC&color=fff&rounded=true"
                                alt="Admin" class="rounded-circle" width="24" height="24">
                            <span class="fw-medium fs-7">Admin User</span>
                        </div>
                    </div>
                </div>

                <!-- Customer Info -->
                <div class="card border-0 shadow-sm rounded-4 glass p-4">
                    <h5 class="fw-bold mb-4">Customer Info</h5>
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <img src="https://ui-avatars.com/api/?name=PT+Sumber+Makmur&background=random"
                            class="rounded-circle" width="48" height="48" alt="Customer">
                        <div>
                            <div class="fw-bold fs-6">PT Sumber Makmur</div>
                            <div class="text-secondary fs-7">tech@sumbermakmur.com</div>
                        </div>
                    </div>

                    <div class="d-flex flex-column gap-3">
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary fs-7">Phone</span>
                            <span class="fw-medium fs-7">+62 812-3456-7890</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary fs-7">Total Licenses</span>
                            <span class="fw-medium fs-7">4 Active</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-secondary fs-7">Previous Tickets</span>
                            <a href="{{ route('admin.tickets.index') }}" class="fs-7 text-decoration-none">View All
                                Tickets</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chat / Thread Area -->
            <div class="col-12 col-xl-8 order-xl-1 d-flex flex-column gap-4">

                <!-- Conversation Thread -->
                <div class="card border-0 shadow-sm rounded-4 glass h-100 d-flex flex-column">
                    <div class="card-header bg-transparent border-bottom border-light p-4">
                        <h5 class="fw-bold mb-0">Conversation History</h5>
                    </div>
                    <div class="card-body p-4 bg-light bg-opacity-50 overflow-auto" style="max-height: 600px;">

                        <!-- Customer Message (Initial) -->
                        <div class="d-flex gap-3 mb-4">
                            <img src="https://ui-avatars.com/api/?name=PT+Sumber+Makmur&background=random"
                                class="rounded-circle" width="40" height="40" alt="Customer">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="fw-bold">PT Sumber Makmur</span>
                                    <span class="badge bg-secondary rounded-pill fs-8">Customer</span>
                                    <span class="text-secondary fs-8 ms-auto">Jul 14, 2026, 09:30 AM</span>
                                </div>
                                <div class="bg-white p-3 rounded-3 shadow-sm border text-dark">
                                    <p class="mb-0">Hello Support Team,</p>
                                    <br>
                                    <p class="mb-0">We just renewed our annual subscription yesterday (Invoice
                                        #INV-2026-902). The payment was successful and the dashboard shows the license as
                                        "Active".</p>
                                    <p class="mb-0">However, when our cashier tries to open the POS application, it keeps
                                        saying "License Expired. Please renew." We have tried restarting the app and logging
                                        out/in but it doesn't work.</p>
                                    <br>
                                    <p class="mb-0">This is very urgent as our store is currently open and we cannot
                                        process transactions.</p>
                                    <br>
                                    <p class="mb-0">Thank you.</p>
                                </div>

                                <!-- Attachments -->
                                <div class="mt-2 d-flex gap-2 flex-wrap">
                                    <button type="button"
                                        class="btn btn-sm btn-light border bg-white rounded-pill px-3 fs-8 text-secondary">
                                        <i class="bi bi-image me-1 text-primary"></i> error_screenshot.png (1.2 MB)
                                    </button>
                                    <button type="button"
                                        class="btn btn-sm btn-light border bg-white rounded-pill px-3 fs-8 text-secondary">
                                        <i class="bi bi-file-pdf me-1 text-danger"></i> payment_receipt.pdf (450 KB)
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Admin Reply -->
                        <div class="d-flex gap-3 mb-4 flex-row-reverse">
                            <img src="https://ui-avatars.com/api/?name=Admin+User&background=0D8ABC&color=fff"
                                class="rounded-circle" width="40" height="40" alt="Admin">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1 flex-row-reverse">
                                    <span class="fw-bold">Admin User</span>
                                    <span class="badge bg-primary rounded-pill fs-8">Support</span>
                                    <span class="text-secondary fs-8 ms-auto">Jul 14, 2026, 09:45 AM</span>
                                </div>
                                <div
                                    class="bg-primary bg-opacity-10 p-3 rounded-3 shadow-sm border border-primary-subtle text-dark">
                                    <p class="mb-0">Hi PT Sumber Makmur,</p>
                                    <br>
                                    <p class="mb-0">I apologize for the inconvenience this has caused. I have checked
                                        your account and confirmed the renewal was successful.</p>
                                    <p class="mb-0">It seems the POS desktop client has cached the old license state.
                                        Please ask your cashier to do the following steps to force a sync:</p>
                                    <ol class="mt-2 mb-0 ps-3">
                                        <li>Open the POS App</li>
                                        <li>Press <strong>CTRL + SHIFT + R</strong> (or go to Settings -> Force Sync)</li>
                                        <li>Wait 5 seconds and restart the app</li>
                                    </ol>
                                    <br>
                                    <p class="mb-0">Let me know if this resolves the issue immediately. I am on standby.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Customer Reply -->
                        <div class="d-flex gap-3">
                            <img src="https://ui-avatars.com/api/?name=PT+Sumber+Makmur&background=random"
                                class="rounded-circle" width="40" height="40" alt="Customer">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <span class="fw-bold">PT Sumber Makmur</span>
                                    <span class="badge bg-secondary rounded-pill fs-8">Customer</span>
                                    <span class="text-secondary fs-8 ms-auto">Jul 14, 2026, 09:55 AM</span>
                                </div>
                                <div class="bg-white p-3 rounded-3 shadow-sm border text-dark">
                                    <p class="mb-0">Hi Admin,</p>
                                    <p class="mb-0">We tried CTRL+SHIFT+R. It showed a loading spinner but then gave an
                                        error "Sync failed: Network timeout". Our internet connection is stable though.</p>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Reply Box -->
                    <div class="card-footer bg-white border-top border-light p-4 rounded-bottom-4">
                        <form action="{{ route('admin.tickets.reply', $ticket->id ?? 1) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="fw-bold mb-2">Write a Reply</label>

                                <!-- Simple Toolbar -->
                                <div class="border border-bottom-0 rounded-top-3 p-2 bg-light d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-light border py-1 px-2"><i
                                            class="bi bi-type-bold"></i></button>
                                    <button type="button" class="btn btn-sm btn-light border py-1 px-2"><i
                                            class="bi bi-type-italic"></i></button>
                                    <div class="vr mx-1"></div>
                                    <button type="button" class="btn btn-sm btn-light border py-1 px-2"><i
                                            class="bi bi-list-ul"></i></button>
                                    <button type="button" class="btn btn-sm btn-light border py-1 px-2"><i
                                            class="bi bi-list-ol"></i></button>
                                    <div class="ms-auto">
                                        <button type="button"
                                            class="btn btn-sm btn-light border py-1 px-3 d-flex align-items-center gap-2">
                                            <i class="bi bi-journal-text text-primary"></i> <span
                                                class="d-none d-sm-inline">Canned Response</span>
                                        </button>
                                    </div>
                                </div>

                                <textarea name="message" class="form-control border rounded-bottom-3 rounded-top-0 shadow-none" rows="5"
                                    placeholder="Type your response here..." required></textarea>
                            </div>

                            <div
                                class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                                <div>
                                    <label class="btn btn-light border rounded-pill px-3 mb-0 hover-lift cursor-pointer">
                                        <i class="bi bi-paperclip me-1"></i> Attach File
                                        <input type="file" class="d-none" multiple>
                                    </label>
                                    <span class="fs-8 text-secondary ms-2">Max 5MB (JPG, PNG, PDF)</span>
                                </div>

                                <div class="d-flex gap-2">
                                    <select name="status" class="form-select border shadow-none rounded-pill"
                                        style="width: auto;">
                                        <option value="in_progress" selected>Keep In Progress</option>
                                        <option value="resolved">Mark Resolved</option>
                                        <option value="pending">Mark Pending (Wait for Cust.)</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm hover-lift">
                                        <i class="bi bi-send me-2"></i> Send Reply
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
