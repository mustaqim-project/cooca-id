@extends('layouts.admin')

@section('title', 'Kelola Template Balasan Cepat Live Chat')

@section('content')
<div class="container-fluid" style="padding: 28px; max-width: 1400px; margin: 0 auto; font-family: 'Inter', system-ui, -apple-system, sans-serif;">

    {{-- Top Hero Banner with Glassmorphism --}}
    <div style="background: linear-gradient(135deg, #0F172A 0%, #1E293B 50%, #075E54 100%); border-radius: 20px; padding: 28px 32px; color: white; margin-bottom: 28px; box-shadow: 0 10px 25px -5px rgba(7, 94, 84, 0.25); position: relative; overflow: hidden;">
        <div style="position: absolute; right: -20px; top: -20px; width: 220px; height: 220px; background: rgba(37, 211, 102, 0.1); border-radius: 50%; blur: 40px; pointer-events: none;"></div>
        
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px; position: relative; z-index: 2;">
            <div>
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                    <span style="background: rgba(37, 211, 102, 0.2); color: #25D366; border: 1px solid rgba(37, 211, 102, 0.3); font-size: 11px; font-weight: 700; padding: 4px 12px; border-radius: 20px; letter-spacing: 0.5px; text-transform: uppercase;">
                        <i class="fa-solid fa-bolt me-1"></i> Quick Response System
                    </span>
                    <span style="color: rgba(255,255,255,0.6); font-size: 12px;">• Live Chat Sync Engine</span>
                </div>
                <h1 style="font-size: 26px; font-weight: 800; margin: 0 0 6px 0; letter-spacing: -0.5px; display: flex; align-items: center; gap: 12px;">
                    ⚡ Kelola Template Balasan Cepat
                </h1>
                <p style="color: #94A3B8; font-size: 14px; margin: 0; max-width: 650px; line-height: 1.5;">
                    Kelola template teks paragraf otomatis yang dapat diklik oleh Admin saat membalas pesan Live Chat customer baik dari Panel Admin maupun dari HP WhatsApp Mobile.
                </p>
            </div>

            <div style="display: flex; align-items: center; gap: 12px;">
                <a href="{{ route('admin.live-chats.index') }}" class="btn" style="background: rgba(255,255,255,0.1); color: white; border: 1px solid rgba(255,255,255,0.2); font-size: 13px; font-weight: 600; padding: 10px 18px; border-radius: 12px; transition: 0.2s; backdrop-filter: blur(10px);">
                    <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Live Chat
                </a>
                <button type="button" class="btn" onclick="openCreateTemplateModal()" style="background: linear-gradient(135deg, #25D366 0%, #128C7E 100%); color: white; border: none; font-size: 13px; font-weight: 700; padding: 10px 20px; border-radius: 12px; box-shadow: 0 4px 14px rgba(37, 211, 102, 0.4); display: flex; align-items: center; gap: 8px; transition: 0.2s;">
                    <i class="fa-solid fa-plus" style="font-size: 15px;"></i> Tambah Template Baru
                </button>
            </div>
        </div>
    </div>

    {{-- Alert Notification --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show border-0" role="alert" style="border-radius: 14px; background: #DEF7EC; color: #03543F; font-size: 14px; padding: 14px 20px; margin-bottom: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.04);">
        <div class="d-flex align-items: center gap: 10px">
            <i class="fa-solid fa-circle-check" style="font-size: 18px; color: #31C48D;"></i>
            <div><strong>Berhasil!</strong> {{ session('success') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- Metrics Cards Bar --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 28px;">
        <div style="background: white; border-radius: 16px; padding: 20px; border: 1px solid #E2E8F0; box-shadow: 0 4px 12px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 16px;">
            <div style="width: 48px; height: 48px; border-radius: 14px; background: #EEF2FF; color: #4F46E5; display: flex; align-items: center; justify-content: center; font-size: 22px;">
                <i class="fa-solid fa-bolt"></i>
            </div>
            <div>
                <div style="font-size: 12px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Total Template</div>
                <div style="font-size: 22px; font-weight: 800; color: #1E293B;">{{ $templates->count() }} Teks Balasan</div>
            </div>
        </div>

        <div style="background: white; border-radius: 16px; padding: 20px; border: 1px solid #E2E8F0; box-shadow: 0 4px 12px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 16px;">
            <div style="width: 48px; height: 48px; border-radius: 14px; background: #ECFDF5; color: #10B981; display: flex; align-items: center; justify-content: center; font-size: 22px;">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <div style="font-size: 12px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Status Aktif</div>
                <div style="font-size: 22px; font-weight: 800; color: #10B981;">{{ $templates->where('is_active', true)->count() }} Template Siap Pakai</div>
            </div>
        </div>

        <div style="background: white; border-radius: 16px; padding: 20px; border: 1px solid #E2E8F0; box-shadow: 0 4px 12px rgba(0,0,0,0.03); display: flex; align-items: center; gap: 16px;">
            <div style="width: 48px; height: 48px; border-radius: 14px; background: #F0FDF4; color: #128C7E; display: flex; align-items: center; justify-content: center; font-size: 22px;">
                <i class="fa-brands fa-whatsapp"></i>
            </div>
            <div>
                <div style="font-size: 12px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Integrasi Realtime</div>
                <div style="font-size: 14px; font-weight: 700; color: #075E54;">100% Synced to Admin & WA</div>
            </div>
        </div>
    </div>

    {{-- Filter & Search Header --}}
    <div style="background: white; border-radius: 16px; padding: 16px 20px; border: 1px solid #E2E8F0; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap; box-shadow: 0 4px 12px rgba(0,0,0,0.02);">
        <div style="position: relative; flex: 1; max-width: 400px;">
            <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #94A3B8; font-size: 14px;"></i>
            <input type="text" id="tmplSearchInput" onkeyup="filterTemplateCards()" placeholder="Cari template berdasarkan judul atau shortcut..." style="width: 100%; padding: 10px 14px 10px 38px; border: 1px solid #CBD5E1; border-radius: 10px; font-size: 13px; outline: none; background: #F8FAFC;">
        </div>
        <div style="font-size: 13px; color: #64748B; font-weight: 600;">
            Menampilkan <strong id="visibleCount">{{ $templates->count() }}</strong> template balasan
        </div>
    </div>

    {{-- Cards Grid Layout --}}
    <div id="templateCardsGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(360px, 1fr)); gap: 24px;">
        @forelse($templates as $tmpl)
        <div class="template-card-item" data-title="{{ strtolower($tmpl->title) }}" data-shortcut="{{ strtolower($tmpl->shortcut) }}" style="background: white; border-radius: 18px; border: 1px solid #E2E8F0; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.04); display: flex; flex-direction: column; transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);" onmouseover="this.style.transform='translateY(-4px)'; this.style.boxShadow='0 12px 25px rgba(0,0,0,0.08)';" onmouseout="this.style.transform='none'; this.style.boxShadow='0 4px 15px rgba(0,0,0,0.04)';">
            
            {{-- Card Header --}}
            <div style="padding: 16px 20px; background: #F8FAFC; border-bottom: 1px solid #E2E8F0; display: flex; align-items: center; justify-content: space-between;">
                <div>
                    <h3 style="font-size: 15px; font-weight: 700; color: #1E293B; margin: 0 0 4px 0;">{{ $tmpl->title }}</h3>
                    <div style="display: flex; align-items: center; gap: 6px;">
                        <code style="font-size: 11px; font-weight: 600; background: #EEF2FF; color: #4F46E5; padding: 2px 8px; border-radius: 6px; border: 1px solid #C7D2FE;">
                            :{{ $tmpl->shortcut }}
                        </code>
                        <button type="button" onclick="copyShortcut('{{ $tmpl->shortcut }}')" style="background: none; border: none; color: #94A3B8; cursor: pointer; font-size: 11px; padding: 0;" title="Salin shortcut">
                            <i class="fa-regular fa-copy"></i>
                        </button>
                    </div>
                </div>
                <div>
                    @if($tmpl->is_active)
                        <span style="background: #DEF7EC; color: #03543F; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 20px; display: inline-flex; align-items: center; gap: 4px;">
                            <span style="width: 6px; height: 6px; background: #31C48D; border-radius: 50%;"></span> Aktif
                        </span>
                    @else
                        <span style="background: #F1F5F9; color: #64748B; font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 20px;">
                            Nonaktif
                        </span>
                    @endif
                </div>
            </div>

            {{-- Card Body / Message Content Preview --}}
            <div style="padding: 18px 20px; flex: 1; background: #FAF9F6; border-bottom: 1px solid #F1F5F9;">
                <div style="font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; display: flex; align-items: center; gap: 6px;">
                    <i class="fa-solid fa-eye" style="color: #128C7E;"></i> Tampilan Balasan WhatsApp:
                </div>
                <div style="background: #DCF8C6; color: #1E293B; border-radius: 12px 12px 2px 12px; padding: 12px 14px; font-size: 12px; line-height: 1.6; white-space: pre-wrap; max-height: 160px; overflow-y: auto; box-shadow: 0 1px 3px rgba(0,0,0,0.06); font-family: inherit;">
                    {{ $tmpl->content }}
                </div>
            </div>

            {{-- Card Footer Actions --}}
            <div style="padding: 12px 20px; background: white; display: flex; align-items: center; justify-content: space-between;">
                <span style="font-size: 11px; color: #94A3B8; font-weight: 600;">Urutan: #{{ $tmpl->sort_order }}</span>
                <div style="display: flex; gap: 8px;">
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick='openEditTemplateModal(@json($tmpl))' style="border-radius: 8px; font-weight: 600; font-size: 12px; padding: 4px 12px;">
                        <i class="fa-solid fa-pen-to-square me-1"></i> Edit
                    </button>
                    <form action="{{ route('admin.live-chat-templates.destroy', $tmpl->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus template ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger" style="border-radius: 8px; font-weight: 600; font-size: 12px; padding: 4px 10px;">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div style="grid-column: 1 / -1; background: white; border-radius: 16px; padding: 40px; text-align: center; color: #64748B; border: 1px dashed #CBD5E1;">
            <i class="fa-solid fa-bolt" style="font-size: 40px; color: #CBD5E1; margin-bottom: 12px; display: block;"></i>
            <h4 style="font-size: 16px; font-weight: 700; color: #1E293B; margin: 0 0 6px 0;">Belum Ada Template Balasan Cepat</h4>
            <p style="font-size: 13px; margin: 0 0 16px 0;">Klik tombol di bawah ini untuk membuat template balasan pertama Anda.</p>
            <button type="button" class="btn btn-primary" onclick="openCreateTemplateModal()" style="background: #128C7E; border: none; font-weight: 700;">
                + Tambah Template Baru
            </button>
        </div>
        @endforelse
    </div>
</div>

{{-- Pure Standalone Floating Overlay Popup Modal --}}
<div id="templateModalOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; z-index: 999999; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(8px); align-items: center; justify-content: center; padding: 20px; overflow-y: auto;" onclick="if(event.target === this) closeTemplatePopup();">
    <div style="background: white; border-radius: 24px; width: 100%; max-width: 780px; max-height: 90vh; overflow-y: auto; box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.4); border: 1px solid rgba(255,255,255,0.2); animation: popupZoom 0.25s cubic-bezier(0.16, 1, 0.3, 1);">
        
        {{-- Popup Header --}}
        <div style="background: linear-gradient(135deg, #054C44 0%, #075E54 50%, #128C7E 100%); color: white; padding: 22px 28px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 10;">
            <div style="display: flex; align-items: center; gap: 14px;">
                <div style="width: 44px; height: 44px; border-radius: 14px; background: rgba(255,255,255,0.15); backdrop-filter: blur(10px); display: flex; align-items: center; justify-content: center; font-size: 20px; color: #25D366; border: 1px solid rgba(255,255,255,0.2);">
                    <i class="fa-solid fa-bolt"></i>
                </div>
                <div>
                    <h5 id="modalTitle" style="font-size: 18px; margin: 0; font-weight: 800; letter-spacing: -0.3px; color: white;">Tambah Template Balasan Cepat</h5>
                    <p style="font-size: 12px; margin: 2px 0 0 0; color: rgba(255,255,255,0.85);">Format balasan otomatis dalam paragraf untuk Live Chat & WA Support</p>
                </div>
            </div>
            <button type="button" onclick="closeTemplatePopup()" style="background: rgba(255,255,255,0.2); border: none; color: white; width: 32px; height: 32px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 16px; transition: 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.35)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                ✕
            </button>
        </div>

        <form id="templateForm" method="POST" action="{{ route('admin.live-chat-templates.store') }}">
            @csrf
            <input type="hidden" name="_method" id="formMethod" value="POST">
            <div style="padding: 24px; background: #F8FAFC;">
                
                {{-- Row 1: Title & Shortcut --}}
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">
                            <i class="fa-solid fa-heading text-primary me-1"></i> Judul Template (Muncul di Tombol Admin) *
                        </label>
                        <input type="text" name="title" id="inputTitle" required placeholder="Contoh: 📋 Cara Register" style="width: 100%; border-radius: 12px; padding: 11px 16px; font-size: 13px; border: 1px solid #CBD5E1; background: white; font-weight: 600; outline: none; box-sizing: border-box;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">
                            <i class="fa-solid fa-key me-1" style="color: #6366F1;"></i> Shortcut / Kunci Unik
                        </label>
                        <input type="text" name="shortcut" id="inputShortcut" placeholder="Contoh: register" style="width: 100%; border-radius: 12px; padding: 11px 16px; font-size: 13px; border: 1px solid #CBD5E1; background: white; outline: none; box-sizing: border-box;">
                    </div>
                </div>

                {{-- Row 2: Content Textarea --}}
                <div style="margin-bottom: 16px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                        <label style="font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.5px;">
                            <i class="fa-solid fa-paragraph me-1" style="color: #128C7E;"></i> Isi Paragraf Balasan *
                        </label>
                        <span style="font-size: 11px; font-weight: 500; color: #94A3B8;">Dukungan format multi-baris / enter</span>
                    </div>
                    <textarea name="content" id="inputContent" oninput="updateLiveModalPreview(this.value)" rows="6" required placeholder="Tuliskan isi balasan otomatis dalam format paragraf berbaris..." style="width: 100%; border-radius: 14px; padding: 14px 16px; font-size: 13px; line-height: 1.6; font-family: inherit; border: 1px solid #CBD5E1; background: white; resize: vertical; outline: none; box-sizing: border-box;"></textarea>
                </div>

                {{-- Row 3: Live WhatsApp Chat Bubble Preview --}}
                <div style="margin-bottom: 16px;">
                    <label style="display: flex; align-items: center; gap: 6px; font-size: 11px; font-weight: 700; color: #075E54; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 6px;">
                        <i class="fa-brands fa-whatsapp" style="font-size: 15px; color: #25D366;"></i> Pratinjau Balon WA Customer (Real-Time Mockup):
                    </label>

                    <div style="background: #ECE5DD; border-radius: 16px; padding: 16px; border: 1px solid #CBD5E1; box-shadow: inset 0 2px 4px rgba(0,0,0,0.04);">
                        <div style="display: flex; flex-direction: column; align-items: flex-start;">
                            <div style="background: white; color: #1E293B; border-radius: 14px 14px 14px 2px; padding: 12px 16px; max-width: 90%; box-shadow: 0 2px 5px rgba(0,0,0,0.08); font-size: 12.5px; line-height: 1.6; white-space: pre-wrap; width: 100%;">
                                <div style="font-weight: 700; font-size: 11px; color: #075E54; margin-bottom: 4px; display: flex; align-items: center; justify-content: space-between;">
                                    <span><i class="fa-solid fa-headset me-1"></i> Admin Cooca.id</span>
                                    <span style="font-size: 10px; color: #94A3B8; font-weight: 500;">Sekarang</span>
                                </div>
                                <div id="modalPreviewBox" style="color: #1E293B;">(Isi pesan balasan akan tampil di sini secara real-time saat Anda mengetik di atas)</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Row 4: Sort Order & Active Checkbox --}}
                <div style="display: flex; align-items: center; justify-content: space-between; background: white; padding: 14px 18px; border-radius: 12px; border: 1px solid #E2E8F0;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <label style="font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase;">Urutan Tampilan:</label>
                        <input type="number" name="sort_order" id="inputSortOrder" value="0" style="width: 80px; border-radius: 8px; padding: 6px 10px; font-size: 13px; border: 1px solid #CBD5E1; outline: none;">
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <input type="checkbox" name="is_active" id="inputIsActive" value="1" checked style="width: 18px; height: 18px; cursor: pointer;">
                        <label for="inputIsActive" style="font-size: 13px; font-weight: 700; color: #1E293B; cursor: pointer; margin: 0;">
                            Aktifkan Template Ini
                        </label>
                    </div>
                </div>
            </div>

            {{-- Popup Footer --}}
            <div style="background: white; border-top: 1px solid #E2E8F0; padding: 16px 24px; display: flex; align-items: center; justify-content: space-between;">
                <button type="button" onclick="closeTemplatePopup()" style="border-radius: 12px; font-weight: 600; font-size: 13px; padding: 10px 20px; background: #F1F5F9; border: 1px solid #CBD5E1; color: #475569; cursor: pointer;">
                    Batal
                </button>
                <button type="submit" style="background: linear-gradient(135deg, #25D366 0%, #128C7E 100%); color: white; border: none; border-radius: 12px; font-weight: 700; font-size: 13.5px; padding: 11px 28px; box-shadow: 0 4px 14px rgba(37, 211, 102, 0.4); display: flex; align-items: center; gap: 8px; cursor: pointer;">
                    <i class="fa-solid fa-floppy-disk" style="font-size: 15px;"></i> Simpan Template Balasan
                </button>
            </div>
        </form>
    </div>
</div>

<style>
@keyframes popupZoom {
    from { opacity: 0; transform: scale(0.92); }
    to { opacity: 1; transform: scale(1); }
}
</style>

<script>
function openCreateTemplateModal() {
    document.getElementById('modalTitle').innerText = 'Tambah Template Balasan Cepat';
    document.getElementById('templateForm').action = "{{ route('admin.live-chat-templates.store') }}";
    document.getElementById('formMethod').value = 'POST';
    document.getElementById('inputTitle').value = '';
    document.getElementById('inputShortcut').value = '';
    document.getElementById('inputContent').value = '';
    document.getElementById('modalPreviewBox').innerText = '(Isi pesan balasan akan tampil di sini secara real-time saat Anda mengetik di atas)';
    document.getElementById('inputSortOrder').value = '0';
    document.getElementById('inputIsActive').checked = true;

    var overlay = document.getElementById('templateModalOverlay');
    overlay.style.display = 'flex';
}

function openEditTemplateModal(tmpl) {
    document.getElementById('modalTitle').innerText = 'Edit Template Balasan Cepat';
    document.getElementById('templateForm').action = "/admin/live-chat-templates/" + tmpl.id;
    document.getElementById('formMethod').value = 'PUT';
    document.getElementById('inputTitle').value = tmpl.title;
    document.getElementById('inputShortcut').value = tmpl.shortcut;
    document.getElementById('inputContent').value = tmpl.content;
    document.getElementById('modalPreviewBox').innerText = tmpl.content || '(Kosong)';
    document.getElementById('inputSortOrder').value = tmpl.sort_order;
    document.getElementById('inputIsActive').checked = !!tmpl.is_active;

    var overlay = document.getElementById('templateModalOverlay');
    overlay.style.display = 'flex';
}

function closeTemplatePopup() {
    var overlay = document.getElementById('templateModalOverlay');
    overlay.style.display = 'none';
}

function updateLiveModalPreview(val) {
    document.getElementById('modalPreviewBox').innerText = val || '(Isi pesan balasan akan tampil di sini secara real-time saat Anda mengetik di atas)';
}

function filterTemplateCards() {
    var query = document.getElementById('tmplSearchInput').value.toLowerCase().trim();
    var cards = document.querySelectorAll('.template-card-item');
    var visible = 0;

    cards.forEach(function(card) {
        var title = card.getAttribute('data-title') || '';
        var shortcut = card.getAttribute('data-shortcut') || '';

        if (title.includes(query) || shortcut.includes(query)) {
            card.style.display = 'flex';
            visible++;
        } else {
            card.style.display = 'none';
        }
    });

    document.getElementById('visibleCount').innerText = visible;
}

function copyShortcut(code) {
    navigator.clipboard.writeText(code).then(function() {
        alert('Shortcut "' + code + '" berhasil disalin!');
    });
}
</script>
@endsection
