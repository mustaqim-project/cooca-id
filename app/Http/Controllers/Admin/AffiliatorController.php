<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AffiliatorResource;
use App\Repositories\Contracts\AffiliatorRepositoryInterface;
use Illuminate\Http\JsonResponse;



final class AffiliatorController extends Controller
{
    public function __construct(
        private readonly AffiliatorRepositoryInterface $affiliatorRepository
    ) {}

    /**
     * Display listing of affiliators.
     */
    public function index(): Response
    {
        $affiliators = $this->affiliatorRepository->paginate(15);

        return view('admin.affiliators.index', [
            'affiliators' => AffiliatorResource::collection($affiliators),
        ]);
    }

    /**
     * Display the specified affiliator.
     */
    public function show(string $id): Response
    {
        $affiliator = $this->affiliatorRepository->find($id);

        if (!$affiliator) {
            abort(404, 'Affiliator not found');
        }

        return view('admin.affiliators.show', [
            'affiliator' => new AffiliatorResource($affiliator),
            'downlines' => AffiliatorResource::collection($affiliator->downlines),
        ]);
    }

    /**
     * Update the specified affiliator.
     */
    public function update(string $id, array $data): JsonResponse
    {
        $affiliator = $this->affiliatorRepository->find($id);

        if (!$affiliator) {
            return response()->json(['message' => 'Affiliator not found'], 404);
        }

        $this->affiliatorRepository->update($id, $data);

        return response()->json([
            'message' => 'Affiliator updated successfully',
            'data' => new AffiliatorResource($affiliator->fresh()),
        ]);
    }

    /**
     * Remove the specified affiliator.
     */
    public function destroy(string $id): JsonResponse
    {
        $affiliator = $this->affiliatorRepository->find($id);

        if (!$affiliator) {
            return response()->json(['message' => 'Affiliator not found'], 404);
        }

        $this->affiliatorRepository->delete($id);

        return response()->json(['message' => 'Affiliator deleted successfully']);
    }
}
