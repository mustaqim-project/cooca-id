<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deal;
use App\Models\Stage;
use App\Models\Pipeline;
use App\Models\Contract;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DealController extends Controller
{
    /**
     * Display a listing of the deals kanban board.
     */
    public function index()
    {
        $pipelines = Pipeline::all();
        $stages = Stage::with(['deals' => function ($query) {
            $query->with('contract')->orderBy('order');
        }])->orderBy('order')->get();

        $deals = Deal::with(['stage', 'contract.customer'])->latest()->get();
        $contracts = Contract::with('customer')->latest()->get();

        return view('admin.deals.index', compact('pipelines', 'stages', 'deals', 'contracts'));
    }

    /**
     * Show the form for creating a new deal.
     */
    public function create()
    {
        $pipelines = Pipeline::all();
        $stages = Stage::orderBy('order')->get();
        $contracts = Contract::with('customer')->latest()->get();
        return view('admin.deals.create', compact('pipelines', 'stages', 'contracts'));
    }

    /**
     * Store a newly created deal in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'price' => 'nullable|numeric|min:0',
            'pipeline_id' => 'required|uuid',
            'stage_id' => 'required|uuid',
            'sources' => 'nullable|string|max:255',
            'products' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'contract_id' => 'nullable|uuid|exists:contracts,id',
            'agreement_document' => 'nullable|file|mimes:pdf,doc,docx,zip|max:10240',
        ]);

        if ($request->hasFile('agreement_document')) {
            $validated['agreement_document'] = '/' . $request->file('agreement_document')->store('agreements', 'public_uploads');
        }

        $validated['created_by'] = auth('admin')->id();

        Deal::create($validated);

        return redirect()->route('admin.deals.index')->with('success', 'Deal berhasil dibuat.');
    }

    /**
     * Display the specified deal.
     */
    public function show(string $id)
    {
        $deal = Deal::with(['stage', 'pipeline', 'contract.customer', 'tasks'])->findOrFail($id);
        return view('admin.deals.show', compact('deal'));
    }

    /**
     * Update the specified deal in storage.
     */
    public function update(Request $request, string $id)
    {
        $deal = Deal::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'price' => 'nullable|numeric|min:0',
            'pipeline_id' => 'sometimes|required|uuid',
            'stage_id' => 'sometimes|required|uuid',
            'sources' => 'nullable|string|max:255',
            'products' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'contract_id' => 'nullable|uuid|exists:contracts,id',
            'agreement_document' => 'nullable|file|mimes:pdf,doc,docx,zip|max:10240',
        ]);

        if ($request->hasFile('agreement_document')) {
            // Delete old file if exists
            if ($deal->agreement_document) {
                $oldPath = public_path(ltrim($deal->agreement_document, '/'));
                if (File::exists($oldPath)) {
                    File::delete($oldPath);
                }
            }
            $validated['agreement_document'] = '/' . $request->file('agreement_document')->store('agreements', 'public_uploads');
        }

        $deal->update($validated);

        return redirect()->route('admin.deals.index')->with('success', 'Deal berhasil diperbarui.');
    }

    /**
     * Remove the specified deal from storage.
     */
    public function destroy(string $id)
    {
        $deal = Deal::findOrFail($id);
        
        if ($deal->agreement_document) {
            $oldPath = public_path(ltrim($deal->agreement_document, '/'));
            if (File::exists($oldPath)) {
                File::delete($oldPath);
            }
        }

        $deal->delete();

        return redirect()->route('admin.deals.index')->with('success', 'Deal berhasil dihapus.');
    }
}
