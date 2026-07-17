@extends('admin.layouts.app')

@section('title', 'Campaign Report')

@section('content')
    <div class="d-flex flex-column gap-4">

        <!-- Header -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('admin.email-campaigns.index') }}"
                    class="btn btn-light border-0 rounded-circle p-2 shadow-sm hover-lift"><i
                        class="bi bi-arrow-left"></i></a>
                <div>
                    <h2 class="mb-1 fw-bold text-capitalize">Campaign Report</h2>
                    <p class="text-secondary mb-0">Analytics and performance metrics.</p>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-light bg-white border shadow-sm rounded-pill px-4 hover-lift text-secondary">
                    <i class="bi bi-download me-2"></i> Export Report
                </button>
                <form action="{{ route('admin.email-campaigns.destroy', 1) }}" method="POST"
                    onsubmit="return confirm('Delete this campaign completely?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger rounded-pill px-4 hover-lift shadow-sm">
                        <i class="bi bi-trash me-2"></i> Delete
                    </button>
                </form>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="row g-4">
            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 glass p-4 h-100">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 48px; height: 48px;">
                            <i class="bi bi-send fs-4"></i>
                        </div>
                        <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1"><i
                                class="bi bi-check-circle me-1"></i> Sent</span>
                    </div>
                    <h3 class="fw-bold mb-1">4,500</h3>
                    <p class="text-secondary mb-0 fs-7">Total Recipients</p>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 glass p-4 h-100">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="bg-info-subtle text-info rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 48px; height: 48px;">
                            <i class="bi bi-envelope-open fs-4"></i>
                        </div>
                        <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1">46.7%</span>
                    </div>
                    <h3 class="fw-bold mb-1">2,100</h3>
                    <p class="text-secondary mb-0 fs-7">Total Opens</p>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 glass p-4 h-100">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 48px; height: 48px;">
                            <i class="bi bi-hand-index-thumb fs-4"></i>
                        </div>
                        <span class="badge bg-success-subtle text-success rounded-pill px-2 py-1">18.7%</span>
                    </div>
                    <h3 class="fw-bold mb-1">840</h3>
                    <p class="text-secondary mb-0 fs-7">Total Clicks</p>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm rounded-4 glass p-4 h-100">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="bg-danger-subtle text-danger rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 48px; height: 48px;">
                            <i class="bi bi-envelope-x fs-4"></i>
                        </div>
                        <span class="badge bg-danger-subtle text-danger rounded-pill px-2 py-1">0.4%</span>
                    </div>
                    <h3 class="fw-bold mb-1">18</h3>
                    <p class="text-secondary mb-0 fs-7">Bounces & Unsubs</p>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <!-- Main Details -->
            <div class="col-12 col-xl-8">
                <!-- Chart -->
                <div class="card border-0 shadow-sm rounded-4 glass mb-4">
                    <div
                        class="card-header bg-transparent border-bottom border-light p-4 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Engagement Timeline</h5>
                    </div>
                    <div class="card-body p-4">
                        <div id="engagementChart" style="min-height: 300px;"></div>
                    </div>
                </div>

                <!-- Email Preview -->
                <div class="card border-0 shadow-sm rounded-4 glass">
                    <div class="card-header bg-transparent border-bottom border-light p-4">
                        <h5 class="fw-bold mb-0">Content Preview</h5>
                    </div>
                    <div class="card-body p-4 bg-light rounded-bottom-4">
                        <div class="bg-white rounded-3 p-4 shadow-sm mx-auto" style="max-width: 600px;">
                            <div class="text-center mb-4">
                                <img src="https://ui-avatars.com/api/?name=Cooca+ID&background=0d6efd&color=fff&rounded=true"
                                    alt="Logo" width="60">
                            </div>
                            <h2 style="font-weight: bold; margin-bottom: 20px; font-size: 24px; color: #333;">Huge Savings
                                on ERP Subscriptions!</h2>
                            <p style="color: #555; line-height: 1.6; margin-bottom: 20px;">Hi [Customer Name],</p>
                            <p style="color: #555; line-height: 1.6; margin-bottom: 20px;">Our Q4 Black Friday promotion is
                                officially live. For the next 48 hours, you can upgrade your current plan or subscribe to
                                new modules at <strong>40% off</strong> the standard rate.</p>

                            <div
                                style="background: #f8f9fa; border-left: 4px solid #0d6efd; padding: 15px; margin-bottom: 25px;">
                                <p style="margin: 0; color: #333;"><strong>Promo Code:</strong> BF-ERP-2026</p>
                            </div>

                            <p style="color: #555; line-height: 1.6; margin-bottom: 30px;">Don't miss out on the biggest
                                sale of the year. Enhance your operational efficiency today.</p>

                            <div class="text-center mb-4">
                                <a href="#"
                                    style="background: #0d6efd; color: white; padding: 12px 30px; text-decoration: none; border-radius: 50px; font-weight: bold; display: inline-block;">Claim
                                    Your Discount</a>
                            </div>

                            <hr style="border: 0; border-top: 1px solid #eee; margin: 30px 0;">
                            <p style="color: #999; font-size: 12px; text-align: center;">You are receiving this email
                                because you opted in to marketing updates.<br><a href="#"
                                    style="color: #0d6efd;">Unsubscribe</a></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Info -->
            <div class="col-12 col-xl-4 d-flex flex-column gap-4">
                <div class="card border-0 shadow-sm rounded-4 glass p-4">
                    <h5 class="fw-bold mb-4">Campaign Info</h5>

                    <div class="mb-4">
                        <label class="text-secondary fs-7 mb-1 d-block">Name</label>
                        <div class="fw-bold fs-5">Q4 Black Friday Promotion</div>
                    </div>

                    <div class="mb-4">
                        <label class="text-secondary fs-7 mb-1 d-block">Subject Line</label>
                        <div class="fw-medium">Huge Savings on ERP Subscriptions!</div>
                    </div>

                    <div class="mb-4">
                        <label class="text-secondary fs-7 mb-1 d-block">Target Audience</label>
                        <div class="fw-medium"><i class="bi bi-people me-1"></i> All Customers</div>
                    </div>

                    <div class="mb-4">
                        <label class="text-secondary fs-7 mb-1 d-block">Status</label>
                        <span
                            class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1">Sent</span>
                    </div>

                    <hr class="border-light my-2">

                    <div class="d-flex justify-content-between text-start my-3">
                        <span class="text-secondary fs-7">Scheduled</span>
                        <span class="fw-medium fs-7">Nov 20, 2026 09:00</span>
                    </div>
                    <div class="d-flex justify-content-between text-start mb-3">
                        <span class="text-secondary fs-7">Sent At</span>
                        <span class="fw-medium fs-7">Nov 20, 2026 09:02</span>
                    </div>
                    <div class="d-flex justify-content-between text-start">
                        <span class="text-secondary fs-7">Created By</span>
                        <span class="fw-medium fs-7">Admin User</span>
                    </div>
                </div>

                <!-- Click Tracking -->
                <div class="card border-0 shadow-sm rounded-4 glass p-4">
                    <h5 class="fw-bold mb-3">Top Link Clicks</h5>

                    <div class="d-flex justify-content-between align-items-center mb-3 p-3 bg-light rounded-3 border">
                        <div class="text-truncate pe-3">
                            <div class="fw-medium fs-7 text-truncate">https://cooca.id/upgrade</div>
                            <div class="text-secondary fs-8">Primary CTA</div>
                        </div>
                        <span class="badge bg-primary rounded-pill">680</span>
                    </div>

                    <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded-3 border">
                        <div class="text-truncate pe-3">
                            <div class="fw-medium fs-7 text-truncate">https://cooca.id/pricing</div>
                            <div class="text-secondary fs-8">Pricing Page</div>
                        </div>
                        <span class="badge bg-secondary rounded-pill">160</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var options = {
                    series: [{
                        name: 'Opens',
                        data: [120, 350, 680, 420, 210, 150, 90, 80]
                    }, {
                        name: 'Clicks',
                        data: [30, 110, 320, 180, 90, 50, 40, 20]
                    }],
                    chart: {
                        type: 'area',
                        height: 300,
                        toolbar: {
                            show: false
                        },
                        fontFamily: 'Inter, sans-serif'
                    },
                    colors: ['#0d6efd', '#198754'],
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 2
                    },
                    xaxis: {
                        categories: ['09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00'],
                        axisBorder: {
                            show: false
                        },
                        axisTicks: {
                            show: false
                        }
                    },
                    yaxis: {
                        labels: {
                            style: {
                                colors: '#6c757d'
                            }
                        }
                    },
                    grid: {
                        borderColor: '#f8f9fa',
                        strokeDashArray: 4,
                    },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.4,
                            opacityTo: 0.05,
                            stops: [0, 100]
                        }
                    },
                    legend: {
                        position: 'top',
                        horizontalAlign: 'right'
                    }
                };

                var chart = new ApexCharts(document.querySelector("#engagementChart"), options);
                chart.render();
            });
        </script>
    @endpush
@endsection
