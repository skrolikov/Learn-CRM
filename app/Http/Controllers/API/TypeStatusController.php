<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\TypeStatus;
use Illuminate\Http\Request;

class TypeStatusController extends Controller
{
    // Получить список клиентов
    public function index()
    {
        return response()->json(TypeStatus::all());
    }

    // Создать клиента
    public function store(Request $request)
    {

        $client = TypeStatus::create($request->all());
        return response()->json($client, 201);
    }

    // Получить клиента по id
    public function show($id)
    {
        $client = TypeStatus::findOrFail($id);
        return response()->json($client);
    }

    // Обновить данные клиента
    public function update(Request $request, $id)
    {
        $client = TypeStatus::findOrFail($id);
        $client->update($request->all());
        return response()->json($client);
    }

    // Удалить клиента
    public function destroy($id)
    {
        TypeStatus::destroy($id);
        return response()->json(null, 204);
    }
}
