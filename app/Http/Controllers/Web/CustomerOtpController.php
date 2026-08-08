<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Notification\WhatsAppService;
use Illuminate\Support\Facades\Log;

class CustomerOtpController extends Controller
{
    public function showNotice(Request $request)
    {
        // Redirect to dashboard if WhatsApp is globally disabled
        if (! (bool) \App\Models\Setting::get('whatsapp.notifications_active', true)) {
            return redirect()->route('customer.dashboard');
        }

        $customer = $request->user('customer');
        if ($customer->phone_verified_at) {
            return redirect()->route('customer.dashboard');
        }

        $needsPhone = empty($customer->phone);

        return view('public.auth.customer.verify-phone', compact('needsPhone'));
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $customer = $request->user('customer');

        if ($customer->phone_verified_at) {
            return redirect()->route('customer.dashboard');
        }

        if ($customer->wa_otp_code !== $request->otp) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid atau salah.']);
        }

        if ($customer->wa_otp_expires_at && $customer->wa_otp_expires_at->isPast()) {
            return back()->withErrors(['otp' => 'Kode OTP sudah kedaluwarsa. Silakan minta kode baru.']);
        }

        $customer->update([
            'phone_verified_at' => now(),
            'wa_otp_code' => null,
            'wa_otp_expires_at' => null,
        ]);

        return redirect()->intended(route('customer.dashboard'))
            ->with('success', 'Nomor WhatsApp berhasil diverifikasi.');
    }

    public function updatePhone(Request $request, WhatsAppService $waService)
    {
        $request->validate([
            'phone' => ['required', 'string', 'max:20'],
        ]);

        $customer = $request->user('customer');

        if ($customer->phone_verified_at) {
            return redirect()->route('customer.dashboard');
        }

        $customer->update([
            'phone' => $request->phone,
        ]);

        // Generate new OTP
        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $customer->update([
            'wa_otp_code' => $otp,
            'wa_otp_expires_at' => now()->addMinutes(10),
        ]);

        try {
            $waService->sendMessage(
                $customer->phone, 
                "Halo {$customer->name}, kode verifikasi Cooca.id Anda adalah: *{$otp}*\n\nKode ini berlaku selama 10 menit. Jangan berikan kode ini kepada siapapun."
            );
        } catch (\Exception $e) {
            Log::error('Failed to send WA OTP on phone update', ['error' => $e->getMessage()]);
            return back()->withErrors(['phone' => 'Gagal mengirim OTP ke nomor tersebut. Pastikan format nomor benar.']);
        }

        return back()->with('status', 'Nomor HP berhasil disimpan dan kode OTP telah dikirim.');
    }

    public function resend(Request $request, WhatsAppService $waService)
    {
        $customer = $request->user('customer');

        if ($customer->phone_verified_at) {
            return redirect()->route('customer.dashboard');
        }

        // Generate new OTP
        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $customer->update([
            'wa_otp_code' => $otp,
            'wa_otp_expires_at' => now()->addMinutes(10),
        ]);

        try {
            $waService->sendMessage(
                $customer->phone, 
                "Halo {$customer->name}, kode verifikasi Cooca.id Anda yang baru adalah: *{$otp}*\n\nKode ini berlaku selama 10 menit. Jangan berikan kode ini kepada siapapun."
            );
        } catch (\Exception $e) {
            Log::error('Failed to resend WA OTP', ['error' => $e->getMessage()]);
            return back()->withErrors(['otp' => 'Gagal mengirim ulang OTP. Silakan coba lagi nanti.']);
        }

        return back()->with('status', 'Kode OTP baru telah dikirim ke WhatsApp Anda.');
    }
}
