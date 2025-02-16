<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DeviceType;
use Illuminate\Http\Request;

class DeviceTypeController extends Controller
{
    // Получить список клиентов
    public function index()
    {
        return response()->json(DeviceType::all());
    }

    // Создать клиента
    public function store(Request $request)
    {

        $client = DeviceType::create($request->all());
        return response()->json($client, 201);
    }

    // Получить клиента по id
    public function show($id)
    {
        $client = DeviceType::findOrFail($id);
        return response()->json($client);
    }

    // Обновить данные клиента
    public function update(Request $request, $id)
    {
        $client = DeviceType::findOrFail($id);
        $client->update($request->all());
        return response()->json($client);
    }

    // Удалить клиента
    public function destroy($id)
    {
        DeviceType::destroy($id);
        return response()->json(null, 204);
    }
}
