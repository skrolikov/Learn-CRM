<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Получить список заявок
    public function index()
    {
        // загрузить связи
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
            foreach ($request->products as $prod) {
                $order->products()->attach($prod['id'], ['quantity' => $prod['quantity'] ?? 1]);
            }
        }

        if ($request->has('services')) {
            $order->services()->attach($request->services);
        }

        return response()->json($order->load(['client', 'device', 'subStatus', 'products', 'services']), 201);
    }

    // Получить заявку по id
    public function show($id)
    {
        $order = Order::with(['client', 'device', 'subStatus', 'products', 'services'])->findOrFail($id);
        return response()->json($order);
    }

    // Обновить заявку
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->update($request->only(['device_id', 'client_id', 'sub_status_id']));

        // Обновляем связи
        if ($request->has('products')) {
            $order->products()->sync([]);
            foreach ($request->products as $prod) {
                $order->products()->attach($prod['id'], ['quantity' => $prod['quantity'] ?? 1]);
            }
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
