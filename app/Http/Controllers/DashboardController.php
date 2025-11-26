<?php

namespace App\Http\Controllers;

use App\Models\Deployment;
use App\Models\Webhook;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index()
    {
        $totalWebhooks = Webhook::count();
        $activeWebhooks = Webhook::active()->count();
        $totalDeployments = Deployment::count();
        $recentDeployments = Deployment::with('webhook')
            ->latest()
            ->take(10)
            ->get();

        $webhooks = Webhook::withCount('deployments')
            ->with('latestDeployment')
            ->latest()
            ->get();

        return view('dashboard', compact(
            'totalWebhooks',
            'activeWebhooks',
            'totalDeployments',
            'recentDeployments',
            'webhooks'
        ));
    }
}
