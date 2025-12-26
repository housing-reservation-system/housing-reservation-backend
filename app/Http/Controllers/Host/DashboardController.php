<?php

namespace App\Http\Controllers\Host;

use App\Http\Controllers\Controller;
use App\Services\Host\DashboardService;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    use ApiResponse;

    protected $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    public function index()
    {
        $userId = Auth::id();
        $stats = $this->dashboardService->getHostDashboardStats($userId);

        return $this->success($stats, __('Dashboard statistics retrieved successfully'));
    }
}
