<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\OrderHistory;
use Illuminate\Http\Request;

class OrderHistoryController extends Controller 
{
    // Получение всей истории изменений (используйте осторожно при большом объёме данных)
    public function index() 
    {
        $history = OrderHistory::with('user:id,name')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($history);
    }

    // Получение истории для конкретного заказа
    public function show($orderId) 
    {
        $history = OrderHistory::where('order_id', $orderId)
            ->with('user:id,name')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($history);
    }

    // Добавление комментария к заказу
    public function store(Request $request) 
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'comment'  => 'required|string',
        ]);

        $history = OrderHistory::create([
            'order_id' => $request->order_id,
            'user_id'  => auth()->id(),
            'comment'  => $request->comment,
        ]);

        // Загружаем связанные данные пользователя для отображения на фронтенде
        $history->load('user:id,name');

        return response()->json($history, 201);
    }

    // Удаление записи истории (если требуется)
    public function destroy($id) 
    {
        $history = OrderHistory::findOrFail($id);
        $history->delete();

        return response()->json(null, 204);
    }
}
