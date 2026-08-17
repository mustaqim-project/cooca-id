@extends('layouts.admin')

@section('title', 'Kelola Template Balasan Cepat Live Chat — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <a href="{{ route('admin.live-chats.index') }}">Live Chat</a>
            <span>/</span>
            <span>Template Balasan</span>
        </div>
        <h1 class="page-title">
            <i class="fa-solid fa-bolt text-warning" style="margin-right: 6px;"></i> Template Balasan Cepat Live Chat
        </h1>
        <p class="page-subtitle">Kelola template pesan otomatis siap pakai yang dapat diklik langsung oleh Admin saat merespon chat pelanggan.</p>
    </div>
    <div class="page-actions flex items-center gap-2" style="flex-wrap: wrap;">
        <a href="{{ route('admin.live-chats.index') }}" class="btn btn-outline btn-sm">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Live Chat
        </a>
        <button type="button" class="btn btn-primary btn-sm" onclick="openCreateTemplateModal()">
            <i class="fa-solid fa-plus"></i> Tambah Template Baru
        </button>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success mb-4"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
@endif

{{-- KPI Metrics Grid --}}
<div class="kpi-grid">
    <div class="kpi-card" style="--kpi-color1: var(--primary); --kpi-color2: var(--accent);">
        <div class="kpi-header">
            <span class="kpi-label">Total Template</span>
            <div class="kpi-icon" style="background: var(--primary-soft); color: var(--primary);">
                <i class="fa-solid fa-bolt"></i>
            </div>
        </div>
        <div class="kpi-value">{{ $templates->count() }}</div>
        <div class="kpi-trend">
            <span class="trend-label">Template pesan terdaftar</span>
        </div>
    </div>

    <div class="kpi-card" style="--kpi-color1: var(--success); --kpi-color2: #059669;">
        <div class="kpi-header">
            <span class="kpi-label">Status Aktif</span>
            <div class="kpi-icon" style="background: var(--success-soft); color: var(--success);">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>
        <div class="kpi-value" style="color: var(--success);">{{ $templates->where('is_active', true)->count() }}</div>
        <div class="kpi-trend">
            <span class="trend-label text-success">Siap dipakai di panel & WA</span>
        </div>
    </div>

    <div class="kpi-card" style="--kpi-color1: var(--accent); --kpi-color2: var(--primary);">
        <div class="kpi-header">
            <span class="kpi-label">Sinkronisasi Realtime</span>
            <div class="kpi-icon" style="background: var(--accent-soft); color: var(--accent);">
                <i class="fa-brands fa-whatsapp"></i>
            </div>
        </div>
        <div class="kpi-value" style="font-size: 18px; color: var(--accent);">100% Aktif</div>
        <div class="kpi-trend">
            <span class="trend-label">Synced to Admin & WA Gateway</span>
        </div>
    </div>
</div>

{{-- Filter & Search Header --}}
<div class="card mb-4">
    <div class="card-body" style="padding: 16px 20px;">
        <div class="flex items-center justify-between" style="flex-wrap: wrap; gap: 12px;">
            <div style="flex: 1; max-width: 420px;">
                <input type="text" id="tmplSearchInput" onkeyup="filterTemplateCards()" class="form-input" placeholder="🔍 Cari judul template atau shortcut...">
            </div>
            <div class="text-xs text-muted font-semibold">
                Menampilkan <strong id="visibleCount" style="color: var(--text);">{{ $templates->count() }}</strong> template balasan
            </div>
        </div>
    </div>
</div>

{{-- Cards Grid Layout --}}
<div id="templateCardsGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 20px;">
    @forelse($templates as $tmpl)
        <div class="card template-card-item" data-title="{{ strtolower($tmpl->title) }}" data-shortcut="{{ strtolower($tmpl->shortcut) }}" style="display: flex; flex-direction: column; border: 1px solid var(--border); box-shadow: var(--shadow-xs);">
            
            {{-- Card Header --}}
            <div class="card-header flex justify-between items-center" style="padding: 14px 18px; border-bottom: 1px solid var(--border); background: var(--bg-secondary);">
                <div>
                    <h3 class="font-bold text-sm" style="color: var(--text); margin: 0 0 4px 0;">{{ $tmpl->title }}</h3>
                    <div class="flex items-center gap-2">
                        <code style="font-size: 11px; font-weight: 700; background: var(--primary-soft); color: var(--primary); padding: 2px 8px; border-radius: 4px;">
                            :{{ $tmpl->shortcut }}
                        </code>
                        <button type="button" class="btn btn-ghost btn-xs" onclick="copyShortcut('{{ $tmpl->shortcut }}')" title="Salin shortcut" style="padding: 2px 6px;">
                            <i class="fa-regular fa-copy"></i>
                        </button>
                    </div>
                </div>
                <div>
                    @if($tmpl->is_active)
                        <span class="badge badge-success" style="font-size: 10px; font-weight: 700;">AKTIF</span>
                    @else
                        <span class="badge badge-muted" style="font-size: 10px;">NONAKTIF</span>
                    @endif
                </div>
            </div>

            {{-- Card Body / Message Content Preview --}}
            <div class="card-body" style="padding: 16px 18px; flex: 1; display: flex; flex-direction: column; gap: 8px;">
                <div class="text-xs font-bold uppercase text-muted" style="letter-spacing: 0.05em;">
                    <i class="fa-solid fa-eye" style="color: var(--primary);"></i> Pratinjau Balasan:
                </div>
                <div style="background: var(--bg-secondary); color: var(--text); border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 12px 14px; font-size: 12.5px; line-height: 1.6; white-space: pre-wrap; word-break: break-word; max-height: 160px; overflow-y: auto; font-family: inherit;">
                    {{ $tmpl->content }}
                </div>
            </div>

            {{-- Card Footer Actions --}}
            <div class="card-footer flex justify-between items-center" style="padding: 12px 18px; border-top: 1px solid var(--border); background: var(--card);">
                <span class="text-xs text-muted font-mono">Urutan: #{{ $tmpl->sort_order }}</span>
                <div class="flex items-center gap-2">
                    <button type="button" class="btn btn-outline btn-xs" onclick='openEditTemplateModal(@json($tmpl))'>
                        <i class="fa-solid fa-pen-to-square"></i> Edit
                    </button>
                    <form action="{{ route('admin.live-chat-templates.destroy', $tmpl->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus template ini?')" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-ghost btn-xs" style="color: var(--danger);" title="Hapus Template">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="card" style="grid-column: 1 / -1;">
            <div class="card-body text-center text-muted" style="padding: 60px 20px;">
                <i class="fa-solid fa-bolt" style="font-size: 40px; color: var(--primary); opacity: 0.4; margin-bottom: 12px; display: block;"></i>
                <div class="font-bold text-base" style="color: var(--text);">Belum Ada Template Balasan Cepat</div>
                <div class="text-xs text-muted mt-1 mb-4">Buat template paragraf siap pakai agar tim support dapat merespon customer lebih cepat.</div>
                <button type="button" class="btn btn-primary btn-sm" onclick="openCreateTemplateModal()">
                    <i class="fa-solid fa-plus"></i> Buat Template Pertama
                </button>
            </div>
        </div>
    @endforelse
</div>

{{-- Pure Standalone Floating Overlay Popup Modal --}}
<div id="templateModalOverlay" style="display: none; position: fixed; inset: 0; z-index: 9999; background: rgba(0, 0, 0, 0.65); backdrop-filter: blur(4px); align-items: center; justify-content: center; padding: 20px;" onclick="if(event.target === this) closeTemplatePopup();">
    <div class="card" style="width: 100%; max-width: 640px; max-height: 90vh; overflow-y: auto; background: var(--card); border: 1px solid var(--border); box-shadow: var(--shadow-lg); border-radius: var(--radius-md);">
        
        {{-- Popup Header --}}
        <div class="card-header flex justify-between items-center" style="padding: 18px 24px; border-bottom: 1px solid var(--border); background: var(--bg-secondary);">
            <div class="flex items-center gap-3">
                <div style="width: 36px; height: 36px; border-radius: 8px; background: var(--primary-soft); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 16px;">
                    <i class="fa-solid fa-bolt"></i>
                </div>
                <div>
                    <div id="modalTitle" class="font-bold text-base" style="color: var(--text);">Tambah Template Balasan Cepat</div>
                    <div class="text-xs text-muted">Format balasan otomatis dalam paragraf untuk Live Chat & WA Support</div>
                </div>
            </div>
            <button type="button" class="btn btn-ghost btn-xs" onclick="closeTemplatePopup()">✕</button>
        </div>

        <form id="templateForm" method="POST" action="{{ route('admin.live-chat-templates.store') }}">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <div class="card-body" style="padding: 24px; display: flex; flex-direction: column; gap: 16px;">
                
                {{-- Row 1: Title & Shortcut --}}
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                    <div class="form-group">
                        <label class="form-label text-xs font-bold uppercase">
                            Judul Template (Di Tombol Admin) *
                        </label>
                        <input type="text" name="title" id="inputTitle" required placeholder="Contoh: Cara Register" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label text-xs font-bold uppercase">
                            Shortcut / Kunci Unik *
                        </label>
                        <input type="text" name="shortcut" id="inputShortcut" placeholder="Contoh: register" class="form-input">
                    </div>
                </div>

                {{-- Row 2: Content Textarea --}}
                <div class="form-group">
                    <div class="flex justify-between items-center mb-1">
                        <label class="form-label text-xs font-bold uppercase" style="margin: 0;">
                            Isi Paragraf Balasan *
                        </label>
                        <span class="text-xs text-muted">Multi-baris / enter</span>
                    </div>
                    <textarea name="content" id="inputContent" oninput="updateLiveModalPreview(this.value)" rows="5" required placeholder="Tuliskan isi balasan otomatis dalam format paragraf..." class="form-textarea"></textarea>
                </div>

                {{-- Row 3: Live Preview --}}
                <div class="form-group">
                    <label class="form-label text-xs font-bold uppercase text-muted">
                        <i class="fa-brands fa-whatsapp" style="color: var(--success);"></i> Pratinjau Balon Chat:
                    </label>
                    <div id="modalPreviewBox" style="background: var(--bg-secondary); color: var(--text); border-radius: 10px; padding: 12px 14px; font-size: 12.5px; line-height: 1.6; white-space: pre-wrap; word-break: break-word; min-height: 50px; border: 1px solid var(--border);">
                        Ketik isi teks di atas untuk melihat preview...
                    </div>
                </div>

                {{-- Row 4: Sort Order & Status --}}
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                    <div class="form-group">
                        <label class="form-label text-xs font-bold uppercase">Nomor Urutan Tampil</label>
                        <input type="number" name="sort_order" id="inputSortOrder" value="0" min="0" class="form-input">
                    </div>
                    <div class="form-group flex items-center gap-2" style="margin-top: 24px;">
                        <input type="checkbox" name="is_active" id="inputIsActive" value="1" checked style="cursor: pointer;">
                        <label for="inputIsActive" class="text-sm font-semibold" style="cursor: pointer; color: var(--text);">Status Aktif</label>
                    </div>
                </div>
            </div>

            <div class="card-footer flex justify-end gap-2" style="padding: 16px 24px; border-top: 1px solid var(--border); background: var(--bg-secondary);">
                <button type="button" class="btn btn-ghost btn-sm" onclick="closeTemplatePopup()">Batal</button>
                <button type="submit" id="modalSubmitBtn" class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Template
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openCreateTemplateModal() {
    document.getElementById('modalTitle').innerText = 'Tambah Template Balasan Cepat';
    document.getElementById('modalSubmitBtn').innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Simpan Template';
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('templateForm').action = '{{ route("admin.live-chat-templates.store") }}';

    document.getElementById('inputTitle').value = '';
    document.getElementById('inputShortcut').value = '';
    document.getElementById('inputContent').value = '';
    document.getElementById('inputSortOrder').value = '0';
    document.getElementById('inputIsActive').checked = true;
    document.getElementById('modalPreviewBox').innerText = 'Ketik isi teks di atas untuk melihat preview...';

    const overlay = document.getElementById('templateModalOverlay');
    overlay.style.display = 'flex';
}

function openEditTemplateModal(tmpl) {
    document.getElementById('modalTitle').innerText = 'Edit Template #' + tmpl.id;
    document.getElementById('modalSubmitBtn').innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Perbarui Template';
    document.getElementById('formMethod').value = 'PUT';
    document.getElementById('templateForm').action = '/admin/live-chat-templates/' + tmpl.id;

    document.getElementById('inputTitle').value = tmpl.title || '';
    document.getElementById('inputShortcut').value = tmpl.shortcut || '';
    document.getElementById('inputContent').value = tmpl.content || '';
    document.getElementById('inputSortOrder').value = tmpl.sort_order ?? 0;
    document.getElementById('inputIsActive').checked = Boolean(tmpl.is_active);
    document.getElementById('modalPreviewBox').innerText = tmpl.content || 'Ketik isi teks di atas untuk melihat preview...';

    const overlay = document.getElementById('templateModalOverlay');
    overlay.style.display = 'flex';
}

function closeTemplatePopup() {
    document.getElementById('templateModalOverlay').style.display = 'none';
}

function updateLiveModalPreview(val) {
    document.getElementById('modalPreviewBox').innerText = val ? val : 'Ketik isi teks di atas untuk melihat preview...';
}

function filterTemplateCards() {
    const query = document.getElementById('tmplSearchInput').value.toLowerCase();
    const cards = document.querySelectorAll('.template-card-item');
    let visible = 0;

    cards.forEach(card => {
        const title = card.getAttribute('data-title') || '';
        const shortcut = card.getAttribute('data-shortcut') || '';

        if (title.includes(query) || shortcut.includes(query)) {
            card.style.display = 'flex';
            visible++;
        } else {
            card.style.display = 'none';
        }
    });

    const counter = document.getElementById('visibleCount');
    if (counter) counter.innerText = visible;
}

function copyShortcut(shortcut) {
    navigator.clipboard.writeText(':' + shortcut).then(() => {
        alert('Shortcut :' + shortcut + ' berhasil disalin!');
    });
}
</script>
@endpush
