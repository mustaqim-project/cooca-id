@extends('layouts.admin')

@section('title', 'Live Chat Support Realtime — COOCA.ID Admin')

@section('content')
<div class="page-header">
    <div>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Admin</a>
            <span>/</span>
            <span>Support & Communications</span>
            <span>/</span>
            <span>Live Chat</span>
        </div>
        <h1 class="page-title">
            <i class="fa-solid fa-comments text-primary" style="margin-right: 6px;"></i> Live Chat Support Realtime
        </h1>
        <p class="page-subtitle">Kelola banyak percakapan customer sekaligus secara realtime & terintegrasi dengan WhatsApp Gateway.</p>
    </div>
    <div class="page-actions flex items-center gap-2" style="flex-wrap: wrap;">
        <a href="{{ route('admin.live-chat-templates.index') }}" class="btn btn-outline btn-sm">
            <i class="fa-solid fa-bolt text-warning"></i> Kelola Template Balasan
        </a>
        <div class="badge badge-success flex items-center gap-2" style="padding: 6px 12px; font-weight: 700; font-size: 11px;">
            <span style="width: 8px; height: 8px; background: currentColor; border-radius: 50%; display: inline-block; animation: pulse 2s infinite;"></span>
            Auto Sync Realtime
        </div>
    </div>
</div>

{{-- Main Chat Container --}}
<div class="card" style="padding: 0; overflow: hidden; border: 1px solid var(--border); box-shadow: var(--shadow-sm);">
    <div style="display: grid; grid-template-columns: 340px 1fr; height: 680px; max-height: calc(100vh - 180px); min-height: 520px;">
        
        {{-- Session Sidebar List --}}
        <div style="background: var(--bg-secondary); border-right: 1px solid var(--border); display: flex; flex-direction: column; overflow: hidden;">
            <div style="padding: 14px 16px; border-bottom: 1px solid var(--border); background: var(--card);">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold uppercase text-muted" style="letter-spacing: 0.05em;">Daftar Percakapan</span>
                    <span id="sessionCount" class="badge badge-primary text-xs">{{ $chats->total() ?? $chats->count() }} Sesi</span>
                </div>
            </div>
            
            <div id="sessionList" style="flex: 1; overflow-y: auto; padding: 12px; display: flex; flex-direction: column; gap: 8px;">
                @forelse($chats as $chat)
                    <div class="chat-session-item" data-id="{{ $chat->id }}" onclick="selectChatSession('{{ $chat->id }}')" style="padding: 12px 14px; border-radius: var(--radius-sm); border: 1px solid var(--border); cursor: pointer; transition: all 0.2s ease; background: var(--card);">
                        <div class="flex justify-between items-start mb-1">
                            <div class="font-bold text-sm" style="color: var(--text);">{{ $chat->customer_name }}</div>
                            @if($chat->status === 'active')
                                <span class="badge badge-success" style="font-size: 10px; font-weight: 700;">AKTIF</span>
                            @else
                                <span class="badge badge-muted" style="font-size: 10px;">BERAKHIR</span>
                            @endif
                        </div>
                        <div class="text-xs font-semibold mb-1" style="color: var(--success); display:flex; flex-direction:column; gap:2px;">
                            <span><i class="fa-brands fa-whatsapp"></i> +{{ $chat->customer_phone }}</span>
                            @if($chat->customer_email)
                                <span class="text-muted" style="font-weight:normal;"><i class="fa-regular fa-envelope text-primary"></i> {{ $chat->customer_email }}</span>
                            @endif
                        </div>
                        <div class="text-xs text-muted" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 290px;">
                            {{ $chat->messages->first()?->message ?? 'Belum ada pesan' }}
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted" style="padding: 40px 16px; font-size: 13px;">
                        <i class="fa-regular fa-comment-dots" style="font-size: 32px; opacity: 0.4; margin-bottom: 8px; display: block;"></i>
                        Belum ada sesi percakapan live chat.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Active Conversation Panel --}}
        <div style="background: var(--card); display: flex; flex-direction: column; overflow: hidden;">
            
            {{-- Panel Header --}}
            <div id="chatHeader" style="padding: 16px 20px; border-bottom: 1px solid var(--border); background: var(--card); display: flex; justify-content: space-between; align-items: center; min-height: 64px;">
                <div>
                    <div id="activeCustomerName" class="font-bold text-base" style="color: var(--text);">Pilih Percakapan</div>
                    <div id="activeCustomerPhone" class="text-xs text-muted">Klik salah satu customer di daftar sebelah kiri untuk membalas secara realtime</div>
                </div>
                <div id="activeActions" style="display: none;">
                    <button type="button" class="btn btn-outline btn-sm" onclick="endCurrentChat()" style="color: var(--danger); border-color: var(--border);">
                        <i class="fa-solid fa-flag-checkered"></i> Akhiri & Kirim Transkrip (WA & Email)
                    </button>
                </div>
            </div>

            {{-- Message Stream Body --}}
            <div id="chatMessageBody" style="flex: 1; padding: 20px; background: var(--bg); overflow-y: auto; display: flex; flex-direction: column; gap: 12px;">
                <div style="text-align: center; color: var(--text-muted); margin: auto; font-size: 14px;">
                    <i class="fa-solid fa-comments" style="font-size: 44px; color: var(--border); margin-bottom: 12px; display: block;"></i>
                    Pilih sesi percakapan di sebelah kiri untuk melihat pesan
                </div>
            </div>

            {{-- Admin Reply Footer --}}
            <div id="chatFooter" style="padding: 16px 20px; border-top: 1px solid var(--border); background: var(--card); display: none;">
                {{-- Quick Reply Templates Bar --}}
                <div style="margin-bottom: 12px; display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                    <span class="text-xs font-bold uppercase text-muted" style="letter-spacing: 0.05em; display: flex; align-items: center; gap: 4px;">
                        <i class="fa-solid fa-bolt" style="color: var(--warning);"></i> Balas Cepat:
                    </span>
                    @forelse($templatesList as $tmpl)
                        <button type="button" class="btn btn-outline btn-xs" onclick="insertTemplateReply('{{ addslashes($tmpl->content) }}')" title="{{ $tmpl->content }}">
                            {{ $tmpl->title }}
                        </button>
                    @empty
                        <span class="text-xs text-muted">Belum ada template.</span>
                    @endforelse
                    <a href="{{ route('admin.live-chat-templates.index') }}" target="_blank" class="text-xs font-bold" style="color: var(--primary); margin-left: 4px; text-decoration: none;">
                        + Tambah Template
                    </a>
                </div>

                <form id="adminReplyForm" onsubmit="submitAdminReply(event)" style="display: flex; gap: 12px; align-items: flex-end;">
                    @csrf
                    <textarea id="adminReplyInput" required rows="2" class="form-textarea" placeholder="Ketik balasan pesan atau klik template cepat di atas..." style="flex: 1; resize: none;"></textarea>
                    <button type="submit" id="adminReplyBtn" class="btn btn-primary" style="height: 44px; padding: 0 20px; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-paper-plane"></i> <span>Kirim Balasan</span>
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
<script>
let activeChatId = null;
let pollInterval = null;
let pusherAdmin = null;

function initAdminPusher() {
    const pusherKey = "{{ env('PUSHER_APP_KEY', '') }}";
    const pusherCluster = "{{ env('PUSHER_APP_CLUSTER', 'ap1') }}";
    if (pusherKey && typeof Pusher !== 'undefined') {
        try {
            pusherAdmin = new Pusher(pusherKey, { cluster: pusherCluster, forceTLS: true });
        } catch (e) {
            console.warn('Pusher init skipped:', e);
        }
    }
}

function escapeHtml(text) {
    if (!text) return '';
    return String(text)
        .replace(/&/g, "&amp;")
        .replace(/</g, "&lt;")
        .replace(/>/g, "&gt;")
        .replace(/"/g, "&quot;")
        .replace(/'/g, "&#039;");
}

function fetchSessionsList() {
    fetch('{{ route("admin.live-chats.sessions-data") }}')
    .then(res => res.json())
    .then(data => {
        if (!data.success || !data.chats) return;
        const container = document.getElementById('sessionList');
        if (!container) return;

        let html = '';
        if (data.chats.length === 0) {
            html = '<div class="text-center text-muted" style="padding: 40px 16px; font-size: 13px;"><i class="fa-regular fa-comment-dots" style="font-size: 32px; opacity: 0.4; margin-bottom: 8px; display: block;"></i>Belum ada sesi percakapan live chat.</div>';
        } else {
            data.chats.forEach(chat => {
                const isSelected = activeChatId === chat.id || activeChatId === String(chat.id);
                const itemBg = isSelected ? 'var(--primary-soft)' : 'var(--card)';
                const itemBorder = isSelected ? 'var(--primary)' : 'var(--border)';
                const badgeClass = chat.status === 'active' ? 'badge-success' : 'badge-muted';
                const badgeText = chat.status === 'active' ? 'AKTIF' : 'BERAKHIR';

                html += `
                    <div class="chat-session-item" data-id="${chat.id}" onclick="selectChatSession('${chat.id}')" style="padding: 12px 14px; border-radius: var(--radius-sm); border: 1px solid ${itemBorder}; cursor: pointer; transition: all 0.2s ease; background: ${itemBg};">
                        <div class="flex justify-between items-start mb-1">
                            <div class="font-bold text-sm" style="color: var(--text);">${escapeHtml(chat.customer_name)}</div>
                            <span class="badge ${badgeClass}" style="font-size: 10px; font-weight: 700;">${badgeText}</span>
                        </div>
                        <div class="text-xs font-semibold mb-1" style="color: var(--success); display:flex; flex-direction:column; gap:2px;">
                            <span><i class="fa-brands fa-whatsapp"></i> +${escapeHtml(chat.customer_phone)}</span>
                            ${chat.customer_email ? `<span class="text-muted" style="font-weight:normal;"><i class="fa-regular fa-envelope text-primary"></i> ${escapeHtml(chat.customer_email)}</span>` : ''}
                        </div>
                        <div class="text-xs text-muted" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 290px;">
                            ${escapeHtml(chat.last_message)}
                        </div>
                    </div>
                `;
            });
        }
        container.innerHTML = html;
    })
    .catch(err => console.error("Poll sessions error:", err));
}

function selectChatSession(id) {
    activeChatId = id;
    loadChatMessages(id);
    fetchSessionsList();
}

function loadChatMessages(id) {
    fetch('/admin/live-chats/' + id + '/messages')
    .then(res => res.json())
    .then(data => {
        if (!data.success || !data.chat) return;

        const chat = data.chat;
        const messages = data.messages || [];

        // Update Header
        document.getElementById('activeCustomerName').innerText = chat.customer_name;
        document.getElementById('activeCustomerPhone').innerHTML = `
            <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap; margin-top:2px;">
                <a href="https://wa.me/${chat.customer_phone.replace(/[^0-9]/g, '')}" target="_blank" style="color: var(--success); font-weight: 700; text-decoration: none;">
                    <i class="fa-brands fa-whatsapp"></i> +${chat.customer_phone}
                </a>
                ${chat.customer_email ? `<span class="text-muted">· <i class="fa-regular fa-envelope text-primary"></i> ${chat.customer_email}</span>` : ''}
                <span>· Status: <span class="badge ${chat.status === 'active' ? 'badge-success' : 'badge-muted'}" style="font-size: 10px;">${chat.status.toUpperCase()}</span></span>
            </div>
        `;
        document.getElementById('activeActions').style.display = chat.status === 'active' ? 'block' : 'none';
        document.getElementById('chatFooter').style.display = chat.status === 'active' ? 'block' : 'none';

        // Render Messages
        const body = document.getElementById('chatMessageBody');
        let html = '';

        if (messages.length === 0) {
            html = '<div class="text-center text-muted" style="margin: auto;">Belum ada pesan dalam sesi ini.</div>';
        } else {
            messages.forEach(msg => {
                const isAdmin = msg.sender_type === 'admin';
                const align = isAdmin ? 'flex-end' : 'flex-start';
                const bubbleBg = isAdmin ? 'var(--primary-soft)' : 'var(--card)';
                const bubbleBorder = isAdmin ? 'var(--primary)' : 'var(--border)';
                const senderColor = isAdmin ? 'var(--primary)' : 'var(--success)';
                const senderLabel = isAdmin ? 'Admin Cooca' : (chat.customer_name || 'Customer');

                html += `
                    <div style="align-self: ${align}; max-width: 78%; display: flex; flex-direction: column; align-items: ${align};">
                        <div style="background: ${bubbleBg}; border: 1px solid ${bubbleBorder}; padding: 10px 14px; border-radius: 12px; box-shadow: var(--shadow-xs);">
                            <div style="font-size: 11px; font-weight: 700; color: ${senderColor}; margin-bottom: 2px;">
                                ${escapeHtml(senderLabel)}
                            </div>
                            <div style="font-size: 13px; color: var(--text); line-height: 1.5; white-space: pre-wrap; word-break: break-word;">
                                ${escapeHtml(msg.message)}
                            </div>
                        </div>
                        <span style="font-size: 10px; color: var(--text-muted); margin-top: 4px; padding: 0 4px; font-family: monospace;">
                            ${msg.created_at ? new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) : ''}
                        </span>
                    </div>
                `;
            });
        }

        body.innerHTML = html;
        body.scrollTop = body.scrollHeight;
    })
    .catch(err => console.error("Load messages error:", err));
}

function submitAdminReply(e) {
    e.preventDefault();
    if (!activeChatId) return;

    const input = document.getElementById('adminReplyInput');
    const btn = document.getElementById('adminReplyBtn');
    const message = input.value.trim();

    if (!message) return;

    btn.disabled = true;

    fetch('/admin/live-chats/' + activeChatId + '/reply', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ message: message })
    })
    .then(res => res.json())
    .then(data => {
        btn.disabled = false;
        if (data.success) {
            input.value = '';
            loadChatMessages(activeChatId);
            fetchSessionsList();
        } else {
            alert(data.error || 'Gagal mengirim pesan.');
        }
    })
    .catch(err => {
        btn.disabled = false;
        alert('Terjadi kesalahan jaringan.');
    });
}

function endCurrentChat() {
    if (!activeChatId) return;
    if (!confirm('Apakah Anda yakin ingin mengakhiri sesi chat ini dan mengirimkan transkrip percakapan ke WhatsApp & Email customer?')) return;

    fetch('/admin/live-chats/' + activeChatId + '/end', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(data.message || 'Sesi berhasil diakhiri & transkrip telah dikirimkan.');
            loadChatMessages(activeChatId);
            fetchSessionsList();
        }
    });
}

function insertTemplateReply(content) {
    const input = document.getElementById('adminReplyInput');
    if (!input) return;
    input.value = (input.value ? input.value + "\n" : '') + content;
    input.focus();
}

// Fast realtime polling loop (2000ms)
document.addEventListener('DOMContentLoaded', function() {
    initAdminPusher();
    pollInterval = setInterval(function() {
        fetchSessionsList();
        if (activeChatId) {
            loadChatMessages(activeChatId);
        }
    }, 2000);
});
</script>
@endpush
