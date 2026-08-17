<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyBankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

final class CompanyBankAccountController extends Controller
{
    /**
     * Display a listing of the company bank accounts.
     */
    public function index(Request $request)
    {
        $accounts = CompanyBankAccount::query()
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->search;
                $q->where(function ($sub) use ($search) {
                    $sub->where('bank_name', 'like', "%{$search}%")
                        ->orWhere('account_number', 'like', "%{$search}%")
                        ->orWhere('account_holder', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), function ($q) use ($request) {
                if ($request->status === 'active') {
                    $q->where('is_active', true);
                } elseif ($request->status === 'inactive') {
                    $q->where('is_active', false);
                }
            })
            ->ordered()
            ->paginate(15)
            ->withQueryString();

        return view('admin.bank-accounts.index', [
            'accounts' => $accounts,
        ]);
    }

    /**
     * Store a newly created bank account in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'bank_name' => ['required', 'string', 'max:255'],
            'bank_code' => ['nullable', 'string', 'max:20'],
            'account_number' => ['required', 'string', 'max:100'],
            'account_holder' => ['required', 'string', 'max:255'],
            'branch' => ['nullable', 'string', 'max:255'],
            'instructions' => ['nullable', 'string', 'max:1000'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'qr_code_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'is_active' => ['sometimes', 'boolean'],
            'is_primary' => ['sometimes', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ], [
            'bank_name.required' => 'Nama bank wajib diisi.',
            'account_number.required' => 'Nomor rekening wajib diisi.',
            'account_holder.required' => 'Nama pemilik rekening wajib diisi.',
        ]);

        $data = [
            'bank_name' => $validated['bank_name'],
            'bank_code' => $validated['bank_code'] ?? null,
            'account_number' => $validated['account_number'],
            'account_holder' => $validated['account_holder'],
            'branch' => $validated['branch'] ?? null,
            'instructions' => $validated['instructions'] ?? null,
            'is_active' => $request->boolean('is_active', true),
            'is_primary' => $request->boolean('is_primary', false),
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
        ];

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('company_banks/logos', 'public');
        }

        if ($request->hasFile('qr_code_image')) {
            $data['qr_code_image'] = $request->file('qr_code_image')->store('company_banks/qrcodes', 'public');
        }

        if ($data['is_primary']) {
            CompanyBankAccount::where('is_primary', true)->update(['is_primary' => false]);
        }

        CompanyBankAccount::create($data);

        return redirect()->route('admin.bank-accounts.index')
            ->with('success', "Rekening bank {$data['bank_name']} ({$data['account_number']}) berhasil ditambahkan.");
    }

    /**
     * Show the edit form (or return JSON for modal).
     */
    public function edit(CompanyBankAccount $bankAccount)
    {
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'data' => array_merge($bankAccount->toArray(), [
                    'logo_url' => $bankAccount->logo_url,
                    'qr_code_url' => $bankAccount->qr_code_url,
                ]),
            ]);
        }

        return view('admin.bank-accounts.edit', [
            'account' => $bankAccount,
        ]);
    }

    /**
     * Update the specified bank account.
     */
    public function update(Request $request, CompanyBankAccount $bankAccount)
    {
        $validated = $request->validate([
            'bank_name' => ['required', 'string', 'max:255'],
            'bank_code' => ['nullable', 'string', 'max:20'],
            'account_number' => ['required', 'string', 'max:100'],
            'account_holder' => ['required', 'string', 'max:255'],
            'branch' => ['nullable', 'string', 'max:255'],
            'instructions' => ['nullable', 'string', 'max:1000'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'qr_code_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'is_active' => ['sometimes', 'boolean'],
            'is_primary' => ['sometimes', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ], [
            'bank_name.required' => 'Nama bank wajib diisi.',
            'account_number.required' => 'Nomor rekening wajib diisi.',
            'account_holder.required' => 'Nama pemilik rekening wajib diisi.',
        ]);

        $data = [
            'bank_name' => $validated['bank_name'],
            'bank_code' => $validated['bank_code'] ?? null,
            'account_number' => $validated['account_number'],
            'account_holder' => $validated['account_holder'],
            'branch' => $validated['branch'] ?? null,
            'instructions' => $validated['instructions'] ?? null,
            'is_active' => $request->boolean('is_active', true),
            'is_primary' => $request->boolean('is_primary', false),
            'sort_order' => (int) ($validated['sort_order'] ?? 0),
        ];

        if ($request->hasFile('logo')) {
            if ($bankAccount->logo && Storage::disk('public')->exists($bankAccount->logo)) {
                Storage::disk('public')->delete($bankAccount->logo);
            }
            $data['logo'] = $request->file('logo')->store('company_banks/logos', 'public');
        }

        if ($request->hasFile('qr_code_image')) {
            if ($bankAccount->qr_code_image && Storage::disk('public')->exists($bankAccount->qr_code_image)) {
                Storage::disk('public')->delete($bankAccount->qr_code_image);
            }
            $data['qr_code_image'] = $request->file('qr_code_image')->store('company_banks/qrcodes', 'public');
        }

        if ($data['is_primary'] && !$bankAccount->is_primary) {
            CompanyBankAccount::where('id', '!=', $bankAccount->id)
                ->where('is_primary', true)
                ->update(['is_primary' => false]);
        }

        $bankAccount->update($data);

        return redirect()->route('admin.bank-accounts.index')
            ->with('success', "Rekening bank {$bankAccount->bank_name} berhasil diperbarui.");
    }

    /**
     * Remove the specified bank account.
     */
    public function destroy(CompanyBankAccount $bankAccount)
    {
        if ($bankAccount->logo && Storage::disk('public')->exists($bankAccount->logo)) {
            Storage::disk('public')->delete($bankAccount->logo);
        }

        if ($bankAccount->qr_code_image && Storage::disk('public')->exists($bankAccount->qr_code_image)) {
            Storage::disk('public')->delete($bankAccount->qr_code_image);
        }

        $name = $bankAccount->bank_name;
        $num = $bankAccount->account_number;
        $bankAccount->delete();

        return redirect()->route('admin.bank-accounts.index')
            ->with('success', "Rekening bank {$name} ({$num}) telah berhasil dihapus.");
    }

    /**
     * Toggle active status.
     */
    public function toggleActive(CompanyBankAccount $bankAccount)
    {
        $bankAccount->update(['is_active' => !$bankAccount->is_active]);

        $status = $bankAccount->is_active ? 'diaktifkan' : 'dinonaktifkan';

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => "Rekening {$bankAccount->bank_name} berhasil {$status}.",
                'is_active' => $bankAccount->is_active,
            ]);
        }

        return back()->with('success', "Rekening {$bankAccount->bank_name} berhasil {$status}.");
    }

    /**
     * Set as primary account.
     */
    public function setPrimary(CompanyBankAccount $bankAccount)
    {
        CompanyBankAccount::where('is_primary', true)->update(['is_primary' => false]);
        $bankAccount->update(['is_primary' => true, 'is_active' => true]);

        return back()->with('success', "Rekening {$bankAccount->bank_name} ({$bankAccount->account_number}) telah disetel sebagai rekening utama.");
    }

    /**
     * Reorder accounts.
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'orders' => ['required', 'array'],
            'orders.*.id' => ['required', 'exists:company_bank_accounts,id'],
            'orders.*.sort_order' => ['required', 'integer', 'min:0'],
        ]);

        foreach ($request->orders as $item) {
            CompanyBankAccount::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['success' => true, 'message' => 'Urutan rekening berhasil diperbarui.']);
    }
}
