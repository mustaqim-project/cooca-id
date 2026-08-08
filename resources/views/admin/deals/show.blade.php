@extends('layouts.admin')

@section('title', '{{ $deal->name ?? "Deal Details" }} — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.deals.index') }}">Deals</a>
            <span>/</span>
            <span>Detail</span>
        </div>
        <h1 class="page-title">{{ $deal->name ?? 'Enterprise Deal' }}</h1>
        <p class="page-subtitle">Pipeline: {{ $deal->pipeline->name ?? '—' }} &rarr; Stage: <strong>{{ $deal->stage->name ?? '—' }}</strong></p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.deals.index') }}" class="btn btn-ghost">← Back</a>
        <form action="{{ route('admin.deals.destroy', $deal->id) }}" method="POST" style="display: inline;">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Delete this deal?')">🗑️ Delete</button>
        </form>
    </div>
</div>

<div class="grid-31">
    <div class="flex-col gap-5">
        <div class="card">
            <div class="card-header"><div class="card-title">Deal Details</div></div>
            <div class="card-body flex-col gap-3">
                <div>
                    <div class="text-xs text-muted font-bold uppercase">Deal Value</div>
                    <div class="font-bold text-success text-2xl">Rp {{ number_format($deal->price ?? 0, 0, ',', '.') }}</div>
                </div>
                <div>
                    <div class="text-xs text-muted font-bold uppercase">Contact Phone</div>
                    <div class="font-semibold text-sm">{{ $deal->phone ?? 'N/A' }}</div>
                </div>
                <div>
                    <div class="text-xs text-muted font-bold uppercase">Lead Sources</div>
                    <div class="font-semibold text-sm">{{ $deal->sources ?? 'N/A' }}</div>
                </div>
                <div>
                    <div class="text-xs text-muted font-bold uppercase">Products of Interest</div>
                    <div class="font-semibold text-sm">{{ $deal->products ?? 'N/A' }}</div>
                </div>
                <div>
                    <div class="text-xs text-muted font-bold uppercase">Notes</div>
                    <div class="text-sm" style="white-space: pre-line;">{{ $deal->notes ?? '—' }}</div>
                </div>
                <div>
                    <div class="text-xs text-muted font-bold uppercase">Created</div>
                    <div class="font-semibold text-sm">{{ optional($deal->created_at)->format('d M Y, H:i') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex-col gap-5">
        {{-- Linked Contract --}}
        <div class="card">
            <div class="card-header"><div class="card-title">📄 Linked Contract</div></div>
            <div class="card-body flex-col gap-3">
                @if($deal->contract)
                    <div>
                        <div class="text-xs text-muted font-bold uppercase">Contract Number</div>
                        <div class="font-bold text-sm">{{ $deal->contract->contract_number ?? $deal->contract->id }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-muted font-bold uppercase">Client</div>
                        <div class="font-semibold text-sm">{{ $deal->contract->customer->name ?? '—' }}</div>
                    </div>
                @else
                    <div class="text-muted text-sm">No contract linked to this deal.</div>
                @endif
            </div>
        </div>

        {{-- Agreement Document --}}
        @if($deal->agreement_document)
        <div class="card">
            <div class="card-header"><div class="card-title">📎 Agreement Document</div></div>
            <div class="card-body">
                <a href="{{ $deal->agreement_document }}" target="_blank" class="btn btn-outline w-full">⬇️ Download / View Agreement</a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
