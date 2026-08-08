@extends('layouts.admin')

@section('title', 'Live Chat Support — Multi-Session Realtime')

@section('content')
<div class="container-fluid" style="padding: 24px;">
    {{-- Header --}}
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;">
        <div>
            <h1 style="font-size: 24px; font-weight: 800; color: #1E293B; margin: 0; display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-comments text-primary"></i> Live Chat Support Realtime
            </h1>
            <p style="color: #64748B; margin: 4px 0 0; font-size: 14px;">
                Kelola banyak percakapan customer sekaligus secara realtime & terintegrasi dengan WhatsApp Gateway.
            </p>
        </div>
        <div style="display: flex; align-items: center; gap: 12px;">
            <a href="{{ route('admin.live-chat-templates.index') }}" class="btn btn-outline-primary" style="font-size: 13px; font-weight: 700; border-radius: 20px; display: flex; align-items: center; gap: 6px;">
                <i class="fa-solid fa-gear"></i> Kelola Template Balasan
            </a>
            <div style="display: flex; align-items: center; gap: 8px; background: #DEF7EC; color: #03543F; padding: 8px 14px; border-radius: 20px; font-size: 13px; font-weight: 600;">
                <span style="width: 8px; height: 8px; background: #31C48D; border-radius: 50%; display: inline-block;"></span>
                Auto Polling Realtime Active
            </div>
        </div>
    </div>


    {{-- Chat Grid --}}
    <div style="display: grid; grid-template-columns: 340px 1fr; gap: 20px; height: 680px; max-height: calc(100vh - 160px);">
        
        {{-- Session Sidebar List --}}
        <div style="background: white; border-radius: 16px; border: 1px solid #E2E8F0; display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
            <div style="padding: 16px; border-bottom: 1px solid #F1F5F9; background: #F8FAFC;">
                <div style="font-size: 12px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px;">Daftar Percakapan Customer</div>
            </div>
            
            <div id="sessionList" style="flex: 1; overflow-y: auto; padding: 12px; display: flex; flex-direction: column; gap: 8px;">
                @forelse($chats as $chat)
                    <div class="chat-session-item" data-id="{{ $chat->id }}" onclick="selectChatSession({{ $chat->id }})" style="padding: 12px 14px; border-radius: 12px; border: 1px solid #E2E8F0; cursor: pointer; transition: all 0.2s; background: white;" onmouseover="this.style.borderColor='#4F46E5'" onmouseout="this.style.borderColor='#E2E8F0'">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 4px;">
                            <div style="font-weight: 700; font-size: 14px; color: #1E293B;">{{ $chat->customer_name }}</div>
                            <span style="font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 10px; {{ $chat->status === 'active' ? 'background: #DEF7EC; color: #03543F;' : 'background: #F1F5F9; color: #64748B;' }}">
                                {{ strtoupper($chat->status) }}
                            </span>
                        </div>
                        <div style="font-size: 12px; color: #059669; font-weight: 600; margin-bottom: 6px;">
                            <i class="fa-brands fa-whatsapp"></i> +{{ $chat->customer_phone }}
                        </div>
                        <div style="font-size: 12px; color: #64748B; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            {{ $chat->messages->first()?->message ?? 'Belum ada pesan' }}
                        </div>
                    </div>
                @empty
                    <div style="text-align: center; color: #94A3B8; padding: 32px 16px; font-size: 13px;">
                        Belum ada sesi percakapan live chat.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Active Conversation Panel --}}
        <div style="background: white; border-radius: 16px; border: 1px solid #E2E8F0; display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.03);">
            
            {{-- Panel Header --}}
            <div id="chatHeader" style="padding: 16px 20px; border-bottom: 1px solid #F1F5F9; background: #F8FAFC; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <div id="activeCustomerName" style="font-weight: 800; font-size: 16px; color: #1E293B;">Pilih Percakapan</div>
                    <div id="activeCustomerPhone" style="font-size: 12px; color: #64748B;">Klik salah satu customer di daftar sebelah kiri untuk membalas secara realtime</div>
                </div>
                <div id="activeActions" style="display: none;">
                    <button type="button" onclick="endCurrentChat()" style="background: #FEE2E2; color: #991B1B; border: none; padding: 8px 14px; border-radius: 8px; font-size: 12px; font-weight: 700; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                        <i class="fa-solid fa-flag-checkered"></i> Akhiri & Kirim Transkrip ke WA
                    </button>
                </div>
            </div>

            {{-- Message Body --}}
            <div id="chatMessageBody" style="flex: 1; padding: 20px; background: #ECE5DD; overflow-y: auto; display: flex; flex-direction: column; gap: 12px;">
                <div style="text-align: center; color: #64748B; margin: auto; font-size: 14px;">
                    <i class="fa-solid fa-comments" style="font-size: 40px; color: #CBD5E1; margin-bottom: 12px; display: block;"></i>
                    Pilih sesi percakapan di sebelah kiri untuk melihat pesan
                </div>
            </div>

            {{-- Admin Reply Footer --}}
            <div id="chatFooter" style="padding: 16px; border-top: 1px solid #E2E8F0; background: white; display: none;">
                {{-- Quick Reply Templates Bar --}}
                <div style="margin-bottom: 12px; display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                    <span style="font-size: 11px; font-weight: 700; color: #64748B; text-transform: uppercase; letter-spacing: 0.5px; margin-right: 4px;">
                        <i class="fa-solid fa-bolt text-warning" style="color: #F59E0B;"></i> Template Balas Cepat:
                    </span>
                    @forelse($templatesList as $tmpl)
                        <button type="button" onclick="insertTemplateReply('{{ $tmpl->shortcut }}')" style="background: #F1F5F9; border: 1px solid #CBD5E1; color: #334155; padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; cursor: pointer; transition: 0.2s;" onmouseover="this.style.background='#E2E8F0'" onmouseout="this.style.background='#F1F5F9'">
                            {{ $tmpl->title }}
                        </button>
                    @empty
                        <span style="font-size: 12px; color: #94A3B8;">Belum ada template.</span>
                    @endforelse
                    <a href="{{ route('admin.live-chat-templates.index') }}" target="_blank" style="font-size: 11px; font-weight: 700; color: #128C7E; text-decoration: underline; margin-left: 4px;">
                        + Kelola Template
                    </a>
                </div>


                <form id="adminReplyForm" onsubmit="submitAdminReply(event)" style="display: flex; gap: 12px; align-items: flex-end;">
                    @csrf
                    <textarea id="adminReplyInput" required rows="3" placeholder="Ketik balasan Anda atau klik tombol template di atas..." style="flex: 1; padding: 10px 14px; border: 1px solid #CBD5E1; border-radius: 10px; font-size: 13px; outline: none; resize: vertical; font-family: inherit; line-height: 1.5;"></textarea>
                    <button type="submit" id="adminReplyBtn" style="background: #128C7E; color: white; border: none; padding: 0 24px; border-radius: 10px; font-weight: 700; font-size: 14px; cursor: pointer; display: flex; align-items: center; gap: 8px; height: 46px;">
                        <i class="fa-solid fa-paper-plane"></i> Balas
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
var activeChatId = null;
var pollInterval = null;

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
    .then(function(res) { return res.json(); })
    .then(function(data) {
        if (!data.success || !data.chats) return;
        var container = document.getElementById('sessionList');
        if (!container) return;

        var html = '';
        if (data.chats.length === 0) {
            html = '<div style="text-align: center; color: #94A3B8; padding: 32px 16px; font-size: 13px;">Belum ada sesi percakapan live chat.</div>';
        } else {
            data.chats.forEach(function(chat) {
                var isSelected = activeChatId === chat.id;
                var bg = isSelected ? '#EEF2FF' : 'white';
                var border = isSelected ? '#6366F1' : '#E2E8F0';
                var badgeStyle = chat.status === 'active' ? 'background: #DEF7EC; color: #03543F;' : 'background: #F1F5F9; color: #64748B;';
                
                html += '<div class="chat-session-item" data-id="' + chat.id + '" onclick="selectChatSession(' + chat.id + ')" style="padding: 12px 14px; border-radius: 12px; border: 1px solid ' + border + '; cursor: pointer; transition: all 0.2s; background: ' + bg + ';" onmouseover="this.style.borderColor=\'#4F46E5\'" onmouseout="this.style.borderColor=\'' + border + '\'">' +
                        '<div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 4px;">' +
                            '<div style="font-weight: 700; font-size: 14px; color: #1E293B;">' + escapeHtml(chat.customer_name) + '</div>' +
                            '<span style="font-size: 10px; font-weight: 700; padding: 2px 8px; border-radius: 10px; ' + badgeStyle + '">' +
                                chat.status.toUpperCase() +
                            '</span>' +
                        '</div>' +
                        '<div style="font-size: 12px; color: #059669; font-weight: 600; margin-bottom: 6px;">' +
                            '<i class="fa-brands fa-whatsapp"></i> +' + escapeHtml(chat.customer_phone) +
                        '</div>' +
                        '<div style="font-size: 12px; color: #64748B; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">' +
                            escapeHtml(chat.last_message) +
                        '</div>' +
                    '</div>';
            });
        }
        container.innerHTML = html;
    });
}

// Poll session list periodically for realtime updates
setInterval(fetchSessionsList, 3000);

function selectChatSession(id) {
    activeChatId = id;
    loadChatMessages(id);
    fetchSessionsList();

    if (pollInterval) clearInterval(pollInterval);
    pollInterval = setInterval(function() {
        if (activeChatId) loadChatMessages(activeChatId, true);
    }, 3000);
}

function loadChatMessages(id, silent) {
    fetch('/admin/live-chats/' + id + '/messages')
    .then(function(res) { return res.json(); })
    .then(function(data) {
        if (!data.success) return;

        var chat = data.chat;
        var messages = data.messages;

        document.getElementById('activeCustomerName').innerText = chat.customer_name;
        document.getElementById('activeCustomerPhone').innerText = 'WhatsApp: +' + chat.customer_phone + ' | Status: ' + chat.status.toUpperCase();
        
        var actionsDiv = document.getElementById('activeActions');
        var footerDiv = document.getElementById('chatFooter');

        if (chat.status === 'active') {
            actionsDiv.style.display = 'block';
            footerDiv.style.display = 'block';
        } else {
            actionsDiv.style.display = 'none';
            footerDiv.style.display = 'none';
        }

        var body = document.getElementById('chatMessageBody');
        var html = '';

        messages.forEach(function(m) {
            var isCustomer = m.sender_type === 'customer';
            var isSystem = m.sender_type === 'system';

            if (isSystem) {
                html += '<div style="text-align: center; margin: 8px 0;"><span style="background: rgba(0,0,0,0.08); padding: 4px 12px; border-radius: 12px; font-size: 11px; color: #475569; font-weight: 600;">' + escapeHtml(m.message) + '</span></div>';
            } else if (isCustomer) {
                html += '<div style="display: flex; flex-direction: column; align-items: flex-start;">' +
                        '<div style="background: white; color: #1E293B; border-radius: 12px 12px 12px 2px; padding: 10px 14px; max-width: 75%; box-shadow: 0 2px 4px rgba(0,0,0,0.06); font-size: 13px; line-height: 1.5; white-space: pre-wrap;">' +
                        '<div style="font-weight: 700; font-size: 11px; color: #075E54; margin-bottom: 2px;">' + escapeHtml(m.sender_name) + '</div>' +
                        escapeHtml(m.message) +
                        '</div></div>';
            } else {
                html += '<div style="display: flex; flex-direction: column; align-items: flex-end;">' +
                        '<div style="background: #DCF8C6; color: #1E293B; border-radius: 12px 12px 2px 12px; padding: 10px 14px; max-width: 75%; box-shadow: 0 2px 4px rgba(0,0,0,0.06); font-size: 13px; line-height: 1.5; white-space: pre-wrap;">' +
                        '<div style="font-weight: 700; font-size: 11px; color: #128C7E; margin-bottom: 2px;">Admin Cooca</div>' +
                        escapeHtml(m.message) +
                        '</div></div>';
            }
        });

        body.innerHTML = html;
        if (!silent) {
            body.scrollTop = body.scrollHeight;
        }

        // Also refresh session list to sync status badges
        fetchSessionsList();
    });
}

function submitAdminReply(e) {
    e.preventDefault();
    if (!activeChatId) return;

    var input = document.getElementById('adminReplyInput');
    var btn = document.getElementById('adminReplyBtn');
    var message = input.value.trim();
    if (!message) return;

    btn.disabled = true;

    fetch('/admin/live-chats/' + activeChatId + '/reply', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ message: message })
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        btn.disabled = false;
        if (data.success) {
            input.value = '';
            loadChatMessages(activeChatId);
            fetchSessionsList();
        } else {
            alert(data.error || 'Gagal membalas');
        }
    });
}

function endCurrentChat() {
    if (!activeChatId) return;
    if (!confirm('Apakah Anda yakin ingin mengakhiri sesi percakapan ini? Transkrip chat akan otomatis dikirimkan ke WhatsApp customer.')) return;

    fetch('/admin/live-chats/' + activeChatId + '/end', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(function(res) { return res.json(); })
    .then(function(data) {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Sesi Diakhiri',
                text: 'Percakapan berhasil diakhiri & transkrip telah dikirimkan ke WA Customer.'
            });
            loadChatMessages(activeChatId);
            fetchSessionsList();
        } else {
            alert(data.error || 'Gagal mengakhiri chat.');
        }
    });
}

var dbTemplatesMap = {};
@foreach($templatesList as $tmpl)
    dbTemplatesMap['{{ $tmpl->shortcut }}'] = @json($tmpl->content);
@endforeach

function insertTemplateReply(shortcutKey) {
    if (dbTemplatesMap[shortcutKey]) {
        var input = document.getElementById('adminReplyInput');
        input.value = dbTemplatesMap[shortcutKey];
        input.focus();
    }
}
</script>
@endsection

