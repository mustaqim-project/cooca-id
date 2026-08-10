<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WhatsAppDevice;
use App\Services\WhatsAppGatewayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WhatsAppDeviceController extends Controller
{
    protected WhatsAppGatewayService $gatewayService;

    public function __construct(WhatsAppGatewayService $gatewayService)
    {
        $this->gatewayService = $gatewayService;
    }

    /**
     * Tampilkan daftar WA API Device milik Admin.
     */
    public function index()
    {
        $adminId = Auth::guard('admin')->id();
        $devices = WhatsAppDevice::where('owner_type', 'admin')
            ->where('owner_id', $adminId)
            ->latest()
            ->get();

        // Sync status dari wa-server secara aman (Normalize strtolower)
        foreach ($devices as $device) {
            $statusRes = $this->gatewayService->getStatus($device->session_id);
            if (isset($statusRes['status'])) {
                $status = strtolower($statusRes['status']);
                if ($status === 'connected') {
                    $device->status = 'connected';
                    if (isset($statusRes['user']['id'])) {
                        $num = explode(':', $statusRes['user']['id'])[0] ?? null;
                        if ($num) $device->phone_number = $num;
                    }
                    $device->save();
                } elseif ($device->status !== 'connected') {
                    $device->status = $status;
                    $device->save();
                }
            }
        }

        return view('admin.whatsapp.index', compact('devices'));
    }

    /**
     * Show form to create a new device.
     */
    public function create()
    {
        return view('admin.whatsapp.create');
    }

    /**
     * Show edit form for device.
     */
    public function edit($key)
    {
        $adminId = Auth::guard('admin')->id();
        $device = WhatsAppDevice::where('owner_type', 'admin')
            ->where('owner_id', $adminId)
            ->where(function ($q) use ($key) {
                $q->where('uuid', $key)->orWhere('id', $key);
            })
            ->firstOrFail();

        return view('admin.whatsapp.edit', compact('device'));
    }

    /**
     * Update device (name/status) basic handler.
     */
    public function update(Request $request, $key)
    {
        $adminId = Auth::guard('admin')->id();
        $device = WhatsAppDevice::where('owner_type', 'admin')
            ->where('owner_id', $adminId)
            ->where(function ($q) use ($key) {
                $q->where('uuid', $key)->orWhere('id', $key);
            })
            ->firstOrFail();

        $request->validate(['name' => 'required|string|max:100']);

        $device->update(['name' => $request->input('name')]);

        return redirect()->route('admin.whatsapp-devices.show', $device->uuid)
            ->with('success', 'Device updated successfully.');
    }

    /**
     * Generate WA API Device Baru untuk Admin.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
        ]);

        $adminId = Auth::guard('admin')->id();

        $device = WhatsAppDevice::create([
            'owner_type' => 'admin',
            'owner_id' => $adminId,
            'name' => $request->name,
            'session_id' => WhatsAppDevice::generateSessionId('admin', $adminId),
            'api_key' => WhatsAppDevice::generateApiKey(),
            'status' => 'connecting',
        ]);

        // Start session di wa-server
        $this->gatewayService->startSession($device->session_id);

        return redirect()->route('admin.whatsapp-devices.show', $device->uuid)
            ->with('success', "WhatsApp API Device '{$device->name}' berhasil dibuat. Silakan scan QR Code!");
    }

    /**
     * Tampilkan Detail Device, QR Code, dan Panduan API (via UUID).
     */
    public function show($key)
    {
        $adminId = Auth::guard('admin')->id();
        $device = WhatsAppDevice::where('owner_type', 'admin')
            ->where('owner_id', $adminId)
            ->where(function ($q) use ($key) {
                $q->where('uuid', $key)->orWhere('id', $key);
            })
            ->firstOrFail();

        // Fetch status & QR dari wa-server
        $statusRes = $this->gatewayService->getStatus($device->session_id);
        $qrRes = $this->gatewayService->getQrCodeData($device->session_id);

        if (!empty($qrRes['qrDataUrl'])) {
            $device->qr_code = $qrRes['qrDataUrl'];
        }

        if (isset($statusRes['status'])) {
            $status = strtolower($statusRes['status']);
            if ($status === 'connected') {
                $device->status = 'connected';
                if (isset($statusRes['user']['id'])) {
                    $num = explode(':', $statusRes['user']['id'])[0] ?? null;
                    if ($num) $device->phone_number = $num;
                }
            } elseif ($device->status !== 'connected') {
                $device->status = $status;
            }
        }

        $device->save();

        $qrCodeDataUrl = $device->qr_code ?: ($qrRes['qrDataUrl'] ?? null);
        $qrHtmlUrl = $this->gatewayService->getQrCodeHtmlUrl($device->session_id);

        return view('admin.whatsapp.show', compact('device', 'qrCodeDataUrl', 'qrHtmlUrl'));
    }

    /**
     * Endpoint AJAX Read-Only untuk Cek Koneksi & QR Code (via UUID).
     */
    public function statusAjax($key)
    {
        $adminId = Auth::guard('admin')->id();
        $device = WhatsAppDevice::where('owner_type', 'admin')
            ->where('owner_id', $adminId)
            ->where(function ($q) use ($key) {
                $q->where('uuid', $key)->orWhere('id', $key);
            })
            ->firstOrFail();

        $statusRes = $this->gatewayService->getStatus($device->session_id);
        $qrRes = $this->gatewayService->getQrCodeData($device->session_id);

        if (!empty($qrRes['qrDataUrl'])) {
            $device->qr_code = $qrRes['qrDataUrl'];
        }

        if (isset($statusRes['status'])) {
            $status = strtolower($statusRes['status']);
            if ($status === 'connected') {
                $device->status = 'connected';
                if (isset($statusRes['user']['id'])) {
                    $num = explode(':', $statusRes['user']['id'])[0] ?? null;
                    if ($num) $device->phone_number = $num;
                }
            } elseif ($device->status !== 'connected') {
                $device->status = $status;
            }
        }

        $device->save();

        return response()->json([
            'status' => $device->status,
            'phone_number' => $device->phone_number,
            'qr_code' => $device->qr_code,
        ]);
    }

    /**
     * Kirim Pesan & File Uji Coba dari Admin Panel UI.
     */
    public function testSend(Request $request, $key)
    {
        $request->validate([
            'target' => 'required|string',
            'message' => 'nullable|string',
            'media_url' => 'nullable|url',
            'file' => 'nullable|file|max:16384',
            'filename' => 'nullable|string|max:100',
        ], [
            'file.max' => 'Ukuran file yang diunggah melebihi batas maksimum 16 MB. Pengiriman otomatis ditolak.',
        ]);

        $adminId = Auth::guard('admin')->id();
        $device = WhatsAppDevice::where('owner_type', 'admin')
            ->where('owner_id', $adminId)
            ->where(function ($q) use ($key) {
                $q->where('uuid', $key)->orWhere('id', $key);
            })
            ->firstOrFail();

        $mediaUrl = $request->input('media_url');
        $filename = $request->input('filename');

        if ($request->hasFile('file')) {
            $uploadedFile = $request->file('file');
            $sizeBytes = $uploadedFile->getSize();
            if ($sizeBytes > 16 * 1024 * 1024) {
                $sizeMb = round($sizeBytes / (1024 * 1024), 2);
                $err = "Gagal: Ukuran file yang diunggah ({$sizeMb} MB) melebihi batas maksimum 16 MB. Pengiriman ditolak.";
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'error' => $err], 422);
                }
                return back()->with('error', $err);
            }
            $filename = $filename ?: $uploadedFile->getClientOriginalName();
            $path = $uploadedFile->store('temp_wa_test', 'public');
            $mediaUrl = asset('storage/' . $path);
        }

        if (!$request->input('message') && !$mediaUrl) {
            $err = 'Harap isi Pesan Teks ATAU unggah File / masukan URL Media.';
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'error' => $err], 400);
            }
            return back()->with('error', $err);
        }

        $result = $this->gatewayService->sendMessage(
            $device->session_id,
            $request->target,
            $request->message ?? '',
            $mediaUrl,
            ['filename' => $filename]
        );

        if (!empty($result['success'])) {
            $msg = "Pesan/File berhasil dikirim ke {$request->target} via '{$device->name}'!";
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => $msg, 'details' => $result]);
            }
            return back()->with('success', $msg);
        }

        $err = "Gagal mengirim pesan/file: " . ($result['error'] ?? 'Terjadi kesalahan');
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => false, 'error' => $err], 500);
        }
        return back()->with('error', $err);
    }


    /**
     * Hapus / Disconnect WA API Device (via UUID).
     */
    public function destroy($key)
    {
        $adminId = Auth::guard('admin')->id();
        $device = WhatsAppDevice::where('owner_type', 'admin')
            ->where('owner_id', $adminId)
            ->where(function ($q) use ($key) {
                $q->where('uuid', $key)->orWhere('id', $key);
            })
            ->firstOrFail();

        // Terminate di wa-server
        $this->gatewayService->deleteSession($device->session_id);

        $device->delete();

        return redirect()->route('admin.whatsapp-devices.index')
            ->with('success', "WhatsApp API Device '{$device->name}' telah dihapus.");
    }
}
