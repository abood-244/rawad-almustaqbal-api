<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Service;
use App\Services\ServiceService;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Http\Requests\UpdateServiceStatusRequest;
use App\Http\Resources\ServiceResource;
use App\Traits\ApiResponse;

class ServiceController extends Controller
{
    use ApiResponse;

    public function __construct(private ServiceService $serviceService)
    {
    }

    public function index(Request $request)
    {
        $all = $request->boolean('all');
        $perPage = config('settings.pagination.default', 15);
        
        $services = $this->serviceService->getServices($all, $perPage);

        if ($all) {
            return $this->success(ServiceResource::collection($services), 'All active services retrieved successfully');
        }

        return $this->successPaginated($services, ServiceResource::class, 'Services retrieved successfully');
    }

    public function store(StoreServiceRequest $request)
    {
        $service = $this->serviceService->createService($request->validated());

        return $this->success(ServiceResource::make($service), 'Service created successfully', 201);
    }

    public function update(UpdateServiceRequest $request, $id)
    {
        $service = Service::findOrFail($id);
        
        $service = $this->serviceService->updateService($service, $request->validated());
        
        return $this->success(ServiceResource::make($service), 'Service updated successfully');
    }

    public function updateStatus(UpdateServiceStatusRequest $request, $id)
    {
        $service = Service::findOrFail($id);
        
        $service = $this->serviceService->updateStatus($service, $request->validated('status'));

        return $this->success(ServiceResource::make($service), 'Service status updated successfully');
    }

    public function destroy($id)
    {
        $service = Service::findOrFail($id);
        
        $this->serviceService->deleteService($service);
        
        return $this->success(null, 'Service deleted successfully');
    }
}
