@extends('customer.layouts.app')

@section('title', 'API Credentials')

@section('content')
    <div class="row justify-content-center">
        <div class="col-12 col-xl-10">
            <!-- Header -->
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('customer.licenses.show', $license->id) }}" class="btn btn-sm btn-light border rounded-pill px-3 hover-lift me-3">
                    <i class="bi bi-arrow-left me-1"></i> Back
                </a>
                <div>
                    <h2 class="mb-1 fw-bold">API Credentials</h2>
                    <p class="text-secondary mb-0">Your secret tokens for accessing the product API.</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 glass overflow-hidden mb-4">
                <div class="card-body p-4 bg-danger-subtle d-flex align-items-center gap-3">
                    <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                        <i class="bi bi-exclamation-triangle fs-5"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-danger mb-1">Keep Your Secrets Safe</h6>
                        <p class="text-danger mb-0 fs-7">Do not share your API tokens with anyone or commit them to public repositories.</p>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 glass overflow-hidden">
                <div class="card-body p-4 p-md-5">
                    
                    <!-- License Key -->
                    <div class="mb-5">
                        <label class="form-label fw-medium text-secondary">License Key</label>
                        <div class="input-group">
                            <input type="text" id="license_key" value="{{ $license->license_key }}" readonly class="form-control bg-light border-end-0 font-monospace text-secondary py-3">
                            <button type="button" onclick="copyToClipboard('license_key')" class="btn btn-light border border-start-0 text-secondary hover-lift px-4" title="Copy to clipboard">
                                <i class="bi bi-clipboard"></i> Copy
                            </button>
                        </div>
                        <div class="form-text mt-2 text-secondary fs-7">Used for identifying your purchase when interacting with support or API.</div>
                    </div>
                    
                    <!-- API Token (if any) -->
                    <div>
                        <label class="form-label fw-medium text-secondary">Access Token / API Key</label>
                        <div class="input-group" x-data="{ showToken: false }">
                            <!-- We might only have the token encrypted or generated on the fly, assuming $license->token exists for demo -->
                            <input :type="showToken ? 'text' : 'password'" id="api_token" value="{{ $license->token ?? 'demo_token_12345_abcdef_xxxxxxxxxx' }}" readonly class="form-control bg-light border-end-0 font-monospace text-secondary py-3">
                            <button type="button" @click="showToken = !showToken" class="btn btn-light border border-start-0 border-end-0 text-secondary hover-lift px-3">
                                <i class="bi" :class="showToken ? 'bi-eye-slash' : 'bi-eye'"></i>
                            </button>
                            <button type="button" onclick="copyToClipboard('api_token')" class="btn btn-light border border-start-0 text-secondary hover-lift px-4" title="Copy to clipboard">
                                <i class="bi bi-clipboard"></i> Copy
                            </button>
                        </div>
                        <div class="form-text mt-2 text-secondary fs-7">
                            Include this token in the <code class="text-primary">Authorization: Bearer</code> header for API requests.
                        </div>
                    </div>
                    
                </div>
                
                <div class="card-footer bg-transparent border-top border-light p-4 d-flex justify-content-end">
                    <button type="button" onclick="alert('Feature to regenerate token is coming soon!')" class="btn btn-outline-secondary rounded-pill px-4 py-2 hover-lift fw-medium">
                        <i class="bi bi-arrow-clockwise me-2"></i> Regenerate Token
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function copyToClipboard(elementId) {
        const copyText = document.getElementById(elementId);
        
        // Save current type, change to text to copy if it's password
        const originalType = copyText.type;
        if (originalType === 'password') {
            copyText.type = 'text';
        }
        
        copyText.select();
        copyText.setSelectionRange(0, 99999); // For mobile devices
        
        navigator.clipboard.writeText(copyText.value).then(() => {
            // Restore original type
            if (originalType === 'password') {
                copyText.type = 'password';
            }
            
            Swal.fire({
                icon: 'success',
                title: 'Copied!',
                text: 'Copied to clipboard.',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        });
    }
</script>
@endpush