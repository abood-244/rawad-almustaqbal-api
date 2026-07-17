<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderService;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderStatusRequest;
use App\Http\Resources\OrderResource;
use App\Traits\ApiResponse;

class OrderController extends Controller
{
    use ApiResponse;

    public function __construct(private OrderService $orderService)
    {
    }

    public function index(\Illuminate\Http\Request $request)
    {
        $perPage = config('settings.pagination.default', 15);
        $all = filter_var($request->query('all', false), FILTER_VALIDATE_BOOLEAN);
        
        $orders = $this->orderService->getOrders($perPage, $all);

        if ($all) {
            return $this->success(OrderResource::collection($orders), 'تم جلب الطلبات بنجاح');
        }

        return $this->successPaginated($orders, OrderResource::class, 'تم جلب الطلبات بنجاح');
    }

    public function store(StoreOrderRequest $request)
    {
        $order = $this->orderService->createOrder($request->validated());

        return $this->success(OrderResource::make($order), 'تم إرسال الطلب بنجاح', 201);
    }

    public function updateStatus(UpdateOrderStatusRequest $request, $id)
    {
        $order = Order::findOrFail($id);
        
        $order = $this->orderService->updateStatus($order, $request->validated('status'));

        return $this->success(OrderResource::make($order), 'تم تحديث حالة الطلب بنجاح');
    }
}
