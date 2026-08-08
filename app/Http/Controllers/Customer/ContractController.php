<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Contract;
use App\Models\License;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

final class ContractController extends Controller
{
    /**
     * Show the contract signing page (with signature canvas).
     */
    public function show(string $licenseId)
    {
        $customer = Auth::user();

        $license = License::where('id', $licenseId)
            ->where('customer_id', $customer->id)
            ->with(['product', 'subscriptionPlan'])
            ->firstOrFail();

        $contract = Contract::where('license_id', $license->id)
            ->where('customer_id', $customer->id)
            ->first();

        // Create draft contract if not exists
        if (!$contract) {
            $contract = Contract::create([
                'license_id'      => $license->id,
                'customer_id'     => $customer->id,
                'contract_number' => Contract::generateContractNumber(),
                'status'          => 'draft',
            ]);
        }

        return view('public.pages.customer.contract', compact('license', 'contract', 'customer'));
    }

    /**
     * Save the customer's electronic signature and mark as signed.
     */
    public function sign(Request $request, string $licenseId)
    {
        $customer = Auth::user();

        $request->validate([
            'signature_data' => ['required', 'string'], // base64 from canvas
        ]);

        $license = License::where('id', $licenseId)
            ->where('customer_id', $customer->id)
            ->firstOrFail();

        $contract = Contract::where('license_id', $license->id)
            ->where('customer_id', $customer->id)
            ->firstOrFail();

        $contract->update([
            'customer_signature_data' => $request->signature_data,
            'status'                  => 'signed',
            'signed_at'               => now(),
        ]);

        return redirect()->route('customer.contracts.download', $license->id)
            ->with('success', 'Kontrak berhasil ditandatangani. PDF siap diunduh.');
    }

    /**
     * Generate and stream the PDF contract.
     */
    public function download(string $licenseId)
    {
        $customer = Auth::user();

        $license = License::where('id', $licenseId)
            ->where('customer_id', $customer->id)
            ->with(['product', 'subscriptionPlan'])
            ->firstOrFail();

        $contract = Contract::where('license_id', $license->id)
            ->where('customer_id', $customer->id)
            ->firstOrFail();

        // Cooca signature path (put a file at storage/app/public/signatures/cooca-signature.png)
        $coocaSignaturePath = 'signatures/cooca-signature.png';

        $pdf = Pdf::loadView('pdf.contract', compact(
            'contract',
            'license',
            'customer',
            'coocaSignaturePath'
        ))
        ->setPaper('A4', 'portrait')
        ->setOption('defaultFont', 'DejaVu Sans')
        ->setOption('isHtml5ParserEnabled', true)
        ->setOption('isRemoteEnabled', false);

        $filename = 'COOCA-Contract-' . $contract->contract_number . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Called when a license is created — auto-generate a draft contract.
     * Can be invoked statically from the license creation flow.
     */
    public static function createForLicense(License $license): Contract
    {
        return Contract::firstOrCreate(
            ['license_id' => $license->id, 'customer_id' => $license->customer_id],
            [
                'contract_number' => Contract::generateContractNumber(),
                'status'          => 'draft',
            ]
        );
    }
}
