@php
$statusColors = [
    \App\Models\Trial::STATUS_DRAFT => 'secondary',
    \App\Models\Trial::STATUS_SUBMITTED => 'info',
    \App\Models\Trial::STATUS_WAITING_APPROVAL => 'warning',
    \App\Models\Trial::STATUS_WAITING_PROVISIONING => 'info',
    \App\Models\Trial::STATUS_PROVISIONING => 'primary',
    \App\Models\Trial::STATUS_DOMAIN_SETUP => 'primary',
    \App\Models\Trial::STATUS_TESTING => 'info',
    \App\Models\Trial::STATUS_ACTIVE_TRIAL => 'success',
    \App\Models\Trial::STATUS_CONVERTED_TO_SUBSCRIPTION => 'success',
    \App\Models\Trial::STATUS_EXPIRED => 'danger',
    \App\Models\Trial::STATUS_REJECTED => 'dark',
    \App\Models\Trial::STATUS_FAILED => 'danger',
];

$statusLabels = [
    \App\Models\Trial::STATUS_DRAFT => 'Draft',
    \App\Models\Trial::STATUS_SUBMITTED => 'Submitted',
    \App\Models\Trial::STATUS_WAITING_APPROVAL => 'Waiting Approval',
    \App\Models\Trial::STATUS_WAITING_PROVISIONING => 'Waiting Provisioning',
    \App\Models\Trial::STATUS_PROVISIONING => 'Provisioning',
    \App\Models\Trial::STATUS_DOMAIN_SETUP => 'Domain Setup',
    \App\Models\Trial::STATUS_TESTING => 'Testing',
    \App\Models\Trial::STATUS_ACTIVE_TRIAL => 'Active Trial',
    \App\Models\Trial::STATUS_CONVERTED_TO_SUBSCRIPTION => 'Converted',
    \App\Models\Trial::STATUS_EXPIRED => 'Expired',
    \App\Models\Trial::STATUS_REJECTED => 'Rejected',
    \App\Models\Trial::STATUS_FAILED => 'Failed',
];

$color = $statusColors[$status] ?? 'secondary';
$label = $statusLabels[$status] ?? $status;
@endphp

<span class="badge bg-{{ $color }}">
    {{ $label }}
</span>
