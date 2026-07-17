<?php

namespace App\Services;

use App\Models\Service;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ServiceService
{
    /**
     * Retrieve paginated services or all active services based on condition.
     */
    public function getServices(bool $all = false, int $perPage = 15): LengthAwarePaginator|Collection
    {
        if ($all) {
            return Service::where('status', 'active')->get();
        }

        return Service::paginate($perPage);
    }

    public function createService(array $data): Service
    {
        if (!isset($data['status'])) {
            $data['status'] = 'active';
        }

        return Service::create($data);
    }

    public function updateService(Service $service, array $data): Service
    {
        $service->update($data);
        return $service;
    }

    public function updateStatus(Service $service, string $status): Service
    {
        $service->status = $status;
        $service->save();
        return $service;
    }

    public function deleteService(Service $service): void
    {
        $service->delete();
    }
}
