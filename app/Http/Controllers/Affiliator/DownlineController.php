<?php

declare(strict_types=1);

namespace App\Http\Controllers\Affiliator;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AffiliatorResource;
use App\Repositories\Contracts\AffiliatorRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

final class DownlineController extends Controller
{
    public function __construct(
        private readonly AffiliatorRepositoryInterface $affiliatorRepository
    ) {}

    /**
     * Display listing of downlines (L2 affiliates).
     */
    public function index(): Response
    {
        $affiliator = Auth::guard('affiliator')->user();
        $downlines = $this->affiliatorRepository->getDownlines($affiliator->getKey());

        return Inertia::render('Affiliator/Downlines/Index', [
            'downlines' => AffiliatorResource::collection($downlines),
        ]);
    }

    public function tree(): \Inertia\Response
    {
        return \Inertia\Inertia::render('Affiliator/Downlines/Tree');
    }

    public function stats(): \Inertia\Response
    {
        return \Inertia\Inertia::render('Affiliator/Downlines/Stats');
    }

}
