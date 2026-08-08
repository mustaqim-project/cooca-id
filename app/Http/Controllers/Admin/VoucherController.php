<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateVoucherRequest;
use App\Http\Resources\Admin\VoucherResource;
use App\Services\Voucher\VoucherService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;



final class VoucherController extends Controller
{
    public function __construct(
        private readonly VoucherService $voucherService
    ) {}

    /**
     * Display listing of vouchers.
     */
    public function index()
    {
        $vouchers = $this->voucherService->paginate(15);

        return view('admin.vouchers.index', [
            'vouchers' => VoucherResource::collection($vouchers),
        ]);
    }

    public function create()
    {
        return view('admin.vouchers.create');
    }

    public function show(string $id)
    {
        $voucher = $this->voucherService->findById($id);
        if (!$voucher) {
            abort(404, 'Voucher not found');
        }
        
        $usages = \App\Models\VoucherUsage::where('voucher_id', $voucher->id)
            ->with(['customer', 'transaction'])
            ->orderBy('used_at', 'desc')
            ->get();

        return view('admin.vouchers.show', [
            'voucher' => new VoucherResource($voucher),
            'usages' => $usages
        ]);
    }

    public function edit(string $id)
    {
        $voucher = $this->voucherService->findById($id);
        if (!$voucher) {
            abort(404, 'Voucher not found');
        }
        return view('admin.vouchers.edit', ['voucher' => new VoucherResource($voucher)]);
    }

    /**
     * Store a newly created voucher.
     */
    public function store(CreateVoucherRequest $request)
    {
        $data = $request->validated();
        $voucher = $this->voucherService->create($data);

        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Voucher created successfully.');
    }

    /**
     * Update the specified voucher.
     */
    public function update(CreateVoucherRequest $request, string $id)
    {
        $voucher = $this->voucherService->findById($id);

        if (!$voucher) {
            return redirect()->route('admin.vouchers.index')->with('error', 'Voucher not found.');
        }

        $this->voucherService->update($id, $request->validated());

        return redirect()->route('admin.vouchers.index')
            ->with('success', 'Voucher updated successfully.');
    }

    /**
     * Activate the specified voucher.
     */
    public function activate(string $id)
    {
        $voucher = $this->voucherService->findById($id);

        if (!$voucher) {
            return redirect()->route('admin.vouchers.index')->with('error', 'Voucher not found.');
        }

        $this->voucherService->activate($id);

        return back()->with('success', 'Voucher activated successfully.');
    }

    /**
     * Deactivate the specified voucher.
     */
    public function deactivate(string $id)
    {
        $voucher = $this->voucherService->findById($id);

        if (!$voucher) {
            return redirect()->route('admin.vouchers.index')->with('error', 'Voucher not found.');
        }

        $this->voucherService->deactivate($id);

        return back()->with('success', 'Voucher deactivated successfully.');
    }

    public function destroy(string $id)
    {
        $voucher = $this->voucherService->findById($id);
        if (!$voucher) {
            return redirect()->route('admin.vouchers.index')->with('error', 'Voucher not found.');
        }
        $voucher->delete();
        return redirect()->route('admin.vouchers.index')->with('success', 'Voucher deleted successfully.');
    }
}
