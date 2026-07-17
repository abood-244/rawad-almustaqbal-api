<?php

namespace App\Services;

use App\Models\Order;
use App\Enums\OrderStatus;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderService
{
    /**
     * Retrieve paginated orders with their associated service.
     */
    public function getOrders(int $perPage = 15, bool $all = false)
    {
        $query = Order::with('service')->orderBy('created_at', 'desc');
        
        if ($all) {
            return $query->get();
        }

        return $query->paginate($perPage);
    }

    /**
     * Create a new order with default NEW status.
     */
    public function createOrder(array $data): Order
    {
        // Business Rule: A newly created order is always 'NEW'
        $data['status'] = OrderStatus::NEW->value;
        
        return Order::create($data);
    }

    /**
     * Update an order's status.
     */
    public function updateStatus(Order $order, string $status): Order
    {
        $order->status = $status;
        $order->save();
        
        return $order;
    }
}
