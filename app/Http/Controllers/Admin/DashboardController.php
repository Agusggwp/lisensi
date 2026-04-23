<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\License;
use App\Models\LicenseActivationLog;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $total = License::query()->count();
        $active = License::query()->where('status', License::STATUS_ACTIVE)->count();
        $expired = License::query()->where('status', License::STATUS_EXPIRED)->count();
        $suspended = License::query()->where('status', License::STATUS_SUSPENDED)->count();

        $latestActivations = LicenseActivationLog::query()
            ->with('license.client')
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.dashboard', [
            'stats' => [
                'total' => $total,
                'active' => $active,
                'expired' => $expired,
                'suspended' => $suspended,
            ],
            'latestActivations' => $latestActivations,
        ]);
    }
}
