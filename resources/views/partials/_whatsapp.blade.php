{{-- WhatsApp Float Button - Dynamic from Database --}}
@php
    $whatsappNumber = setting('contact.whatsapp', '6282114468467');
    $whatsappMessage = setting('contact.whatsapp_message', 'Hello, I want to know more about COOCA');
@endphp

<a href="https://wa.me/{{ $whatsappNumber }}?text={{ urlencode($whatsappMessage) }}" 
   class="whatsapp-float" 
   target="_blank" 
   rel="noopener" 
   aria-label="Chat on WhatsApp">
    <span class="pulse-ring"></span>
    <i class="bi bi-whatsapp"></i>
</a>

<style>
.whatsapp-float {
    position: fixed;
    bottom: 28px;
    right: 28px;
    z-index: 999;
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: #25d366;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.6rem;
    box-shadow: 0 6px 24px rgba(37, 211, 102, 0.35);
    transition: all var(--transition);
    text-decoration: none;
}

.whatsapp-float:hover {
    transform: scale(1.1);
    box-shadow: 0 10px 32px rgba(37, 211, 102, 0.5);
    color: #fff;
}

.whatsapp-float .pulse-ring {
    position: absolute;
    inset: -6px;
    border-radius: 50%;
    border: 2px solid #25d366;
    animation: pulse-ring 2s ease-out infinite;
}

@keyframes pulse-ring {
    0% { transform: scale(0.8); opacity: 1; }
    100% { transform: scale(1.6); opacity: 0; }
}

@media (max-width: 480px) {
    .whatsapp-float {
        width: 48px;
        height: 48px;
        bottom: 20px;
        right: 16px;
        font-size: 1.4rem;
    }
    .whatsapp-float .pulse-ring {
        inset: -4px;
    }
}
</style>
