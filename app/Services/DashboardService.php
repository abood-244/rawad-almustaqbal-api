<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Project;
use App\Models\Service;
use App\Http\Resources\OrderResource;
use Illuminate\Support\Facades\Cache;

class DashboardService
{
    private const CACHE_KEY = 'dashboard_stats';

    public function getDashboardData(): array
    {
        return [
            'statistics' => $this->getStatistics(),
            'recentOrders' => $this->getRecentOrders()
        ];
    }

    private function getStatistics(): array
    {
        return Cache::remember(self::CACHE_KEY, 60 * 60, function () {
            return [
                'ordersCount' => Order::count(),
                'projectsCount' => Project::count(),
                'servicesCount' => Service::count(),
                'pendingOrdersCount' => Order::whereIn('status', ['new', 'pending'])->count(),
                'completedOrdersCount' => Order::where('status', 'completed')->count(),
            ];
        });
    }

    private function getRecentOrders()
    {
        $recentOrders = Order::with('service')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        return OrderResource::collection($recentOrders);
    }

    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
