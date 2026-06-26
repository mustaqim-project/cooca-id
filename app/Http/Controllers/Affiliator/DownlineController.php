<?php

declare(strict_types=1);

namespace App\Http\Controllers\Affiliator;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AffiliatorResource;
use App\Repositories\Contracts\AffiliatorRepositoryInterface;
use Illuminate\Support\Facades\Auth;



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

        return view('affiliator.downlines.index', [
            'downlines' => AffiliatorResource::collection($downlines),
        ]);
    }

    public function tree(): Illuminate\View\View
    {
        return view('affiliator.downlines.tree');
    }

    public function stats(): Illuminate\View\View
    {
        return view('affiliator.downlines.stats');
    }

}
