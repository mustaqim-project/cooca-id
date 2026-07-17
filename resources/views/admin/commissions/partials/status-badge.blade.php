@php
$statusColors = [
    'pending' => 'warning',
    'available' => 'success',
    'requested' => 'info',
    'cleared' => 'primary',
    'cancelled' => 'secondary',
    'voided' => 'dark',
];

$statusLabels = [
    'pending' => 'Pending (Holding)',
    'available' => 'Available',
    'requested' => 'Requested',
    'cleared' => 'Cleared',
    'cancelled' => 'Cancelled',
    'voided' => 'Voided',
];

$color = $statusColors[$status] ?? 'secondary';
$label = $statusLabels[$status] ?? ucfirst($status);
@endphp

<span class="badge bg-{{ $color }}">
    {{ $label }}
</span>
