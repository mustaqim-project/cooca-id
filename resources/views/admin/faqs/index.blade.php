@extends('layouts.admin')

@section('title', 'FAQs Management — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <span>FAQs</span>
        </div>
        <h1 class="page-title">Frequently Asked Questions</h1>
        <p class="page-subtitle">Manage landing page FAQ categories, answers, and accordions.</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.faqs.create') }}" class="btn btn-primary">
            <span>❓</span> Add New FAQ
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="data-table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Question</th>
                        <th>Category</th>
                        <th>Order</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($faqs ?? [] as $faq)
                        @php $fObj = is_array($faq) ? (object)$faq : $faq; @endphp
                        <tr>
                            <td class="font-bold text-base">{{ $fObj->question ?? 'Question' }}</td>
                            <td><span class="badge badge-purple">{{ $fObj->category ?? 'General' }}</span></td>
                            <td class="font-semibold">{{ $fObj->sort_order ?? 0 }}</td>
                            <td>
                                <div class="td-actions">
                                    <a href="{{ route('admin.faqs.edit', $fObj->id ?? 1) }}" class="btn btn-ghost btn-sm">✏️ Edit</a>
                                    <form action="{{ route('admin.faqs.destroy', $fObj->id ?? 1) }}" method="POST" onsubmit="return confirm('Delete FAQ?');" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-ghost btn-sm text-danger">🗑️</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted" style="padding: 40px;">No FAQs added yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
