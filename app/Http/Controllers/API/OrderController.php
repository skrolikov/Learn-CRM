<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderHistory;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Получить список заявок
    public function index()
    {
        $orders = Order::with([
            'client',
            'device',
            'device.model',
            'device.brand',
            'subStatus',
            'products',
            'services'
        ])->get();

        return response()->json($orders);
    }

    // Создать заявку
    public function store(Request $request)
    {
        $order = Order::create($request->only(['device_id', 'client_id', 'sub_status_id']));

        if ($request->has('products')) {
            $order->products()->sync(collect($request->products)->pluck('id')->mapWithKeys(fn($id) => [$id => ['quantity' => 1]]));
        }

        if ($request->has('services')) {
            $order->services()->sync($request->services);
        }

        return response()->json($order->load(['client', 'device', 'subStatus', 'products', 'services']), 201);
    }

    // Получить заявку по id
    public function show($id)
    {
        $order = Order::with(['client', 'device', 'subStatus', 'products', 'services'])->findOrFail($id);
        return response()->json($order);
    }

    // Фиксируем изменения в истории
    private function logOrderChange($order, $userId, $newData)
    {
        foreach ($newData as $field => $newValue) {
            $oldValue = $order->$field;

            if ($oldValue != $newValue) {
                OrderHistory::create([
                    'order_id' => $order->id,
                    'user_id' => $userId,
                    'field' => $field,
                    'old_value' => $oldValue,
                    'new_value' => $newValue,
                ]);
            }
        }
    }

    // Обновить заявку
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $userId = auth()->id();

        // Отслеживание изменений
        $this->logOrderChange($order, $userId, $request->only(['device_id', 'client_id', 'sub_status_id']));

        // Обновляем заявку
        $order->update($request->only(['device_id', 'client_id', 'sub_status_id']));

        // Обновляем связи
        if ($request->has('products')) {
            $order->products()->sync(collect($request->products)->pluck('id')->mapWithKeys(fn($id) => [$id => ['quantity' => 1]]));
        }

        if ($request->has('services')) {
            $order->services()->sync($request->services);
        }

        return response()->json($order->load(['client', 'device', 'subStatus', 'products', 'services']));
    }

    // Удалить заявку
    public function destroy($id)
    {
        Order::destroy($id);
        return response()->json(null, 204);
    }
}
